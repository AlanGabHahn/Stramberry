<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Movie;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class MovieFactory extends Factory
{
    /**
     * @var string
     */
    protected $model = Movie::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {   
        $genre = Genre::first();
        return [
            'title' => fake()->name(),
            'release_month' => 12,
            'release_year' => 2023,
            'genre_id' => $genre->id 
        ];
    }
}
