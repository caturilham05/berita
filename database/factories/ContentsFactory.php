<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contents>
 */
class ContentsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tag_ids'  => '3,1,4',
            'cat_ids'  => '1,2',
            'title'   => $this->faker->sentence(),
            'intro'   => $this->faker->sentence(),
            'image'   => $this->faker->imageUrl(640,480),
            'content' => $this->faker->text(),
        ];
    }
}
