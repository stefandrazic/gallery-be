<?php

namespace Database\Factories;

use App\Models\Gallery;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userCount = User::count();
        $authorId = $userCount > 0 ? $this->faker->numberBetween(1, $userCount) : 1;
        $galleryCount = Gallery::count();
        $galleryId = $galleryCount > 0 ? $this->faker->numberBetween(1, $galleryCount) : 1;

        if ($authorId > 0 && $galleryCount > 0) {
            return [
                'content' => fake()->sentence(4),
                'gallery_id' => $galleryId,
                'author_id' => $authorId,
            ];
        }
        return [];
    }
}
