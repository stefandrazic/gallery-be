<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gallery>
 */
class GalleryFactory extends Factory
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
        if ($authorId > 0) {
            return [
                'name' => fake()->sentence(3),
                'description' => fake()->sentence(6),
                'img_urls' => "https://www.simplilearn.com/ice9/free_resources_article_thumb/what_is_image_Processing.jpg,https://letsenhance.io/static/8f5e523ee6b2479e26ecc91b9c25261e/1015f/MainAfter.jpg",
                'author_id' => $authorId
            ];
        }
        return [];
    }
}
