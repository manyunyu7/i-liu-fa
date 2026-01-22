<?php

namespace Tests\Unit;

use App\Models\Dream;
use App\Models\DreamCategory;
use App\Models\Level;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DreamTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected DreamCategory $category;

    protected function setUp(): void
    {
        parent::setUp();

        Level::factory()->create(['level_number' => 1, 'xp_required' => 0]);
        $this->user = User::factory()->create();
        $this->category = DreamCategory::factory()->system()->create();
    }

    public function test_can_manifest_dream(): void
    {
        $dream = Dream::factory()->create([
            'user_id' => $this->user->id,
            'dream_category_id' => $this->category->id,
            'manifestation_date' => null,
            'status' => 'dreaming',
        ]);

        $dream->manifest();

        $this->assertNotNull($dream->fresh()->manifestation_date);
        $this->assertEquals('manifested', $dream->fresh()->status);
    }

    public function test_manifesting_dream_awards_xp(): void
    {
        $dream = Dream::factory()->create([
            'user_id' => $this->user->id,
            'dream_category_id' => $this->category->id,
            'xp_reward' => 200,
        ]);

        $initialXp = $this->user->total_xp;

        $dream->manifest();

        $this->assertEquals($initialXp + 200, $this->user->fresh()->total_xp);
    }

    public function test_dream_has_journal_entries_relationship(): void
    {
        $dream = Dream::factory()->create([
            'user_id' => $this->user->id,
            'dream_category_id' => $this->category->id,
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class, $dream->journalEntries());
    }

    public function test_dream_belongs_to_user(): void
    {
        $dream = Dream::factory()->create([
            'user_id' => $this->user->id,
            'dream_category_id' => $this->category->id,
        ]);

        $this->assertInstanceOf(User::class, $dream->user);
        $this->assertEquals($this->user->id, $dream->user->id);
    }

    public function test_dream_belongs_to_category(): void
    {
        $dream = Dream::factory()->create([
            'user_id' => $this->user->id,
            'dream_category_id' => $this->category->id,
        ]);

        $this->assertInstanceOf(DreamCategory::class, $dream->category);
        $this->assertEquals($this->category->id, $dream->category->id);
    }
}
