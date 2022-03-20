<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => 1,
            'title' => $this->faker->sentence(),
            'slug' => $this->faker->slug(),
            'excerpt' => implode('', $this->faker->sentences(3)),
            'body' => implode('<br>', $this->faker->paragraphs(3)),
            'category_id' => rand(1, 5),
            'prev_article' => $this->faker->url(),
            'next_article' => $this->faker->url(),
            'image' => 'https://picsum.photos/1200/600',
            'featured' => rand(0, 1),
            'active' => rand(0, 1),
        ];
    }
}
