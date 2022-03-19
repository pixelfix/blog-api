<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(1)->create([
            'name' => 'Daniel Swanepoel',
            'displayname' => 'Keiser',
            'email' => 'daniel@pixelfix.net.au',
            'role_id' => 1
        ]);

        Role::factory()->create(['name' => 'Administrator', 'slug' => 'administrator']);
        Role::factory()->create(['name' => 'User', 'slug' => 'user']);

        Category::factory(1)->create(['name' => 'ReactJS', 'slug' => 'reactjs', 'colour' => 'blue']);
        Category::factory(1)->create(['name' => 'JavaScript', 'slug' => 'javascript', 'colour' => 'yellow']);
        Category::factory(1)->create(['name' => 'Laravel', 'slug' => 'laravel', 'colour' => 'red']);
        Category::factory(1)->create(['name' => 'HTML', 'slug' => 'html', 'colour' => 'green']);
        Category::factory(1)->create(['name' => 'CSS', 'slug' => 'css', 'colour' => 'indigo']);

        User::factory(10)->create();
        Tag::factory(10)->create();
        // Category::factory(10)->create();
        Post::factory(100)->create();
        PostComment::factory(300)->create();
    }
}
