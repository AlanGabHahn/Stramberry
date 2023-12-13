<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Genre;
use App\Models\Streaming;
use App\Models\User;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=0;$i<4;$i++) {
            $names = [
                "Eduarda",
                "Peeter",
                "Alan",
                "Felicia"
            ];
            $streamingName = [
                "amazon",
                "netflix",
                "disney",
                "hbo",
            ];
            $genreName = [
                "Comedia",
                "terror",
                "ação",
                "drama"
            ];
            
            $user = new User();
            $user->name = $names[rand(0, count($names)-1)];
            $user->save();

            $genre = new Genre();
            $genre->name = $genreName[rand(0, count($names)-1)];
            $genre->save();

            $streaming = new Streaming();
            $streaming->name = $streamingName[rand(0, count($names)-1)];
            $streaming->save();

        }
    }
}
