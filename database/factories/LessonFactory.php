<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lesson>
 */
class LessonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_id' => null,
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(10),
            'video_url' => $this->faker->optional()->url(),
            'order' => $this->faker->numberBetween(1, 10),
        ];
    }
}

