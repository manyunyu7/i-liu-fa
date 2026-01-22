<?php

namespace Tests\Feature;

use App\Models\Level;
use App\Models\User;
use App\Models\VisionBoard;
use App\Models\VisionBoardItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VisionBoardTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Level::factory()->create(['level_number' => 1, 'title' => 'Dreamer', 'xp_required' => 0]);
        $this->user = User::factory()->create();
    }

    public function test_guest_cannot_access_vision_boards(): void
    {
        $response = $this->get('/vision-board');

        $response->assertRedirect('/login');
    }

    public function test_user_can_view_vision_boards_index(): void
    {
        $response = $this->actingAs($this->user)->get('/vision-board');

        $response->assertStatus(200);
        $response->assertSee('Vision Boards');
    }

    public function test_user_can_view_create_page(): void
    {
        $response = $this->actingAs($this->user)->get('/vision-board/create');

        $response->assertStatus(200);
    }

    public function test_user_can_create_vision_board(): void
    {
        $response = $this->actingAs($this->user)->post('/vision-board', [
            'title' => 'My Dream Life',
            'description' => 'Visualizing my goals',
            'theme' => 'cosmic',
            'is_primary' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('vision_boards', [
            'user_id' => $this->user->id,
            'title' => 'My Dream Life',
            'theme' => 'cosmic',
        ]);
    }

    public function test_vision_board_requires_title(): void
    {
        $response = $this->actingAs($this->user)->post('/vision-board', [
            'title' => '',
            'theme' => 'default',
        ]);

        $response->assertSessionHasErrors('title');
    }

    public function test_user_can_view_own_vision_board(): void
    {
        $board = VisionBoard::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->get("/vision-board/{$board->id}");

        $response->assertStatus(200);
        $response->assertSee($board->title);
    }

    public function test_user_cannot_view_other_users_private_board(): void
    {
        $otherUser = User::factory()->create();
        $board = VisionBoard::factory()->create([
            'user_id' => $otherUser->id,
            'is_public' => false,
        ]);

        $response = $this->actingAs($this->user)->get("/vision-board/{$board->id}");

        $response->assertStatus(403);
    }

    public function test_user_can_view_public_board(): void
    {
        $otherUser = User::factory()->create();
        $board = VisionBoard::factory()->public()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($this->user)->get("/vision-board/{$board->id}");

        $response->assertStatus(200);
    }

    public function test_user_can_update_vision_board(): void
    {
        $board = VisionBoard::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->put("/vision-board/{$board->id}", [
            'title' => 'Updated Title',
            'theme' => 'nature',
            'is_public' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('vision_boards', [
            'id' => $board->id,
            'title' => 'Updated Title',
            'theme' => 'nature',
        ]);
    }

    public function test_user_can_delete_vision_board(): void
    {
        $board = VisionBoard::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->delete("/vision-board/{$board->id}");

        $response->assertRedirect('/vision-board');
        $this->assertDatabaseMissing('vision_boards', ['id' => $board->id]);
    }

    public function test_user_can_add_item_to_board(): void
    {
        $board = VisionBoard::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->postJson("/vision-board/{$board->id}/items", [
            'type' => 'quote',
            'content' => 'Believe in yourself',
            'position_x' => 100,
            'position_y' => 100,
            'width' => 200,
            'height' => 100,
        ]);

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('vision_board_items', [
            'vision_board_id' => $board->id,
            'type' => 'quote',
            'content' => 'Believe in yourself',
        ]);
    }

    public function test_user_can_delete_item_from_board(): void
    {
        $board = VisionBoard::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $item = VisionBoardItem::factory()->create([
            'vision_board_id' => $board->id,
        ]);

        $response = $this->actingAs($this->user)->deleteJson("/vision-board/items/{$item->id}");

        $response->assertJson(['success' => true]);
        $this->assertDatabaseMissing('vision_board_items', ['id' => $item->id]);
    }

    public function test_primary_board_is_unique(): void
    {
        $board1 = VisionBoard::factory()->primary()->create([
            'user_id' => $this->user->id,
        ]);

        $board2 = VisionBoard::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $board2->makePrimary();

        $this->assertFalse($board1->fresh()->is_primary);
        $this->assertTrue($board2->fresh()->is_primary);
    }
}
