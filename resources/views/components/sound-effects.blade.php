{{-- Sound Effects Component --}}
{{-- This component provides audio feedback for user interactions --}}

@php
    $user = auth()->user();
    $soundEnabled = $user?->preferences['sound_enabled'] ?? true;
    $hapticEnabled = $user?->preferences['haptic_enabled'] ?? true;
    $volume = $user?->preferences['volume'] ?? 0.5;
@endphp

<div x-data="soundEffects({{ json_encode(['enabled' => $soundEnabled, 'haptic' => $hapticEnabled, 'volume' => $volume]) }})"
     x-init="init()"
     class="hidden">
</div>

@once
    @push('scripts')
    <script>
        function soundEffects(config) {
            return {
                enabled: config.enabled,
                hapticEnabled: config.haptic,
                volume: config.volume,
                sounds: {},

                init() {
                    // Define sound effects with Web Audio API compatible frequencies
                    this.sounds = {
                        success: { frequency: 880, type: 'sine', duration: 150 },
                        complete: { frequency: [523, 659, 784], type: 'sine', duration: 100 },
                        click: { frequency: 440, type: 'square', duration: 50 },
                        levelUp: { frequency: [392, 494, 587, 784], type: 'sine', duration: 150 },
                        reward: { frequency: [659, 784, 988], type: 'triangle', duration: 120 },
                        streak: { frequency: [523, 659, 784, 1047], type: 'sine', duration: 100 },
                        xp: { frequency: 1047, type: 'sine', duration: 80 },
                        error: { frequency: [349, 311], type: 'square', duration: 200 },
                        notification: { frequency: [659, 523], type: 'sine', duration: 150 },
                    };

                    // Listen for sound events
                    window.addEventListener('play-sound', (e) => {
                        this.play(e.detail.sound);
                    });

                    // Auto-play sounds on certain elements
                    this.attachListeners();
                },

                attachListeners() {
                    // Button clicks
                    document.querySelectorAll('button, .btn, [role="button"]').forEach(el => {
                        el.addEventListener('click', () => this.play('click'));
                    });

                    // Form submissions (success sound handled by server response)
                    document.querySelectorAll('form').forEach(form => {
                        form.addEventListener('submit', () => this.play('click'));
                    });

                    // Checkboxes and toggles
                    document.querySelectorAll('input[type="checkbox"]').forEach(el => {
                        el.addEventListener('change', (e) => {
                            if (e.target.checked) {
                                this.play('success');
                            } else {
                                this.play('click');
                            }
                        });
                    });
                },

                play(soundName) {
                    if (!this.enabled) return;

                    const sound = this.sounds[soundName];
                    if (!sound) return;

                    try {
                        const audioContext = new (window.AudioContext || window.webkitAudioContext)();

                        if (Array.isArray(sound.frequency)) {
                            // Play chord or sequence
                            sound.frequency.forEach((freq, index) => {
                                setTimeout(() => {
                                    this.playTone(audioContext, freq, sound.type, sound.duration);
                                }, index * (sound.duration * 0.7));
                            });
                        } else {
                            this.playTone(audioContext, sound.frequency, sound.type, sound.duration);
                        }

                        // Haptic feedback
                        if (this.hapticEnabled && navigator.vibrate) {
                            navigator.vibrate(sound.duration / 2);
                        }
                    } catch (e) {
                        console.log('Sound playback not available');
                    }
                },

                playTone(audioContext, frequency, type, duration) {
                    const oscillator = audioContext.createOscillator();
                    const gainNode = audioContext.createGain();

                    oscillator.connect(gainNode);
                    gainNode.connect(audioContext.destination);

                    oscillator.type = type;
                    oscillator.frequency.setValueAtTime(frequency, audioContext.currentTime);

                    // Apply volume
                    gainNode.gain.setValueAtTime(this.volume * 0.3, audioContext.currentTime);

                    // Fade out
                    gainNode.gain.exponentialRampToValueAtTime(
                        0.001,
                        audioContext.currentTime + duration / 1000
                    );

                    oscillator.start(audioContext.currentTime);
                    oscillator.stop(audioContext.currentTime + duration / 1000);
                },

                // Methods that can be called from Alpine components
                playSuccess() { this.play('success'); },
                playComplete() { this.play('complete'); },
                playClick() { this.play('click'); },
                playLevelUp() { this.play('levelUp'); },
                playReward() { this.play('reward'); },
                playStreak() { this.play('streak'); },
                playXp() { this.play('xp'); },
                playError() { this.play('error'); },
                playNotification() { this.play('notification'); },

                // Update settings
                toggleSound() {
                    this.enabled = !this.enabled;
                    this.savePreference('sound_enabled', this.enabled);
                    if (this.enabled) this.play('click');
                },

                toggleHaptic() {
                    this.hapticEnabled = !this.hapticEnabled;
                    this.savePreference('haptic_enabled', this.hapticEnabled);
                    if (this.hapticEnabled && navigator.vibrate) {
                        navigator.vibrate(100);
                    }
                },

                setVolume(value) {
                    this.volume = value;
                    this.savePreference('volume', this.volume);
                    this.play('click');
                },

                async savePreference(key, value) {
                    try {
                        await fetch('/api/preferences', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ [key]: value })
                        });
                    } catch (e) {
                        console.log('Failed to save preference');
                    }
                }
            };
        }

        // Global helper to play sounds
        window.playSound = function(soundName) {
            window.dispatchEvent(new CustomEvent('play-sound', { detail: { sound: soundName } }));
        };
    </script>
    @endpush
@endonce
