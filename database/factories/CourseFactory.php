<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->sentence(3);

        return [
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name) . '-' . time(),
            'price' => $this->faker->randomFloat(2, 100, 5000),
            'description' => $this->faker->paragraph(5),
            'status' => $this->faker->randomElement(['draft', 'published']),
        ];
    }
}

