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
        $imgNumber = $this->faker->numberBetween(1, 4);

        if ($authorId > 0) {
            $imageUrls = [];
            for ($i = 0; $i < $imgNumber; $i++) {
                // Generate fake image URLs, you can replace this with your actual logic
                $imageUrls[] = $this->faker->imageUrl();
            }

            return [
                'name' => $this->faker->sentence(3),
                'description' => $this->faker->sentence(6),
                'img_urls' => implode(',', $imageUrls),
                'author_id' => $authorId
            ];
        }
        return [];
    }
}
