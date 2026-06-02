<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Image;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Creamos 3 usuarios
        User::factory(3)->create()->each(function ($user) {

            // Por cada usuario, creamos 5 imágenes
            Image::factory(5)->create([
                'user_id' => $user->id,
            ])->each(function ($image) use ($user) {

                // Por cada imagen, creamos 3 comentarios de usuarios aleatorios
                Comment::factory(3)->create([
                    'user_id'  => User::inRandomOrder()->first()->id,
                    'image_id' => $image->id,
                ]);

                // Por cada imagen, creamos 2 likes de usuarios aleatorios
                Like::factory(2)->create([
                    'user_id'  => User::inRandomOrder()->first()->id,
                    'image_id' => $image->id,
                ]);
            });
        });
    }
}
