<?php

namespace Database\Factories;

use App\Enum\Music\StatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Music>
 */
class MusicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->name(),
            'youtube_id' => $this->faker->randomNumber(),
            'status' => $this->faker->randomElement(StatusEnum::cases()),
            'user_id' => UserFactory::new(),
            'thumb' => $this->faker->name(),
            'count_views' => $this->faker->randomNumber(5)
        ];
    }
}
