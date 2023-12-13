<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\Assessment;
use App\Models\User;
use App\Models\Streaming;

class ControllerTest extends TestCase
{   

    use RefreshDatabase;

   /**
    * @return void
    */
    public function test_criarGenre_retornaOk_quandoPassaOsDados()
    {
        $response = $this->postJson('api/genre', [
            "name" => "Gênero Teste"
        ]);
        $response->assertStatus(200);
        $_SESSION['genre_id_test'] = $response['id'];
    }

    /**
    * @return void
    */
    public function test_criarStreaming_retornaOk_quandoPassaOsDados()
    {
        $response = $this->postJson('api/streaming', [
            "name" => "Streaming Teste"
        ]);
        $response->assertStatus(200);
        $_SESSION['streaming_id_test'] = $response['id'];
    }

    /**
    * @return void
    */
    public function test_criarUser_retornaOk_quandoPassaOsDados()
    {
        $response = $this->postJson('api/user', [
            "name" => "Usuário Teste"
        ]);
        $response->assertStatus(200);
        $_SESSION['user_id_test'] = $response['id'];
    }

    /**
    * @return void
    */
    public function test_criarMovie_retornaOk_quandoPassaOsDados()
    {
        $response = $this->postJson('api/movies', [
            "title" => "Filme titulo",
            "release_month" => "12",
            "release_year" => "2023",
            "genre_id" => $_SESSION['genre_id_test']
        ]);
        $response->assertStatus(200);
        $_SESSION['movie_id_test'] = $response['id'];
    }

    /**
    * @return void
    */
    public function test_buscarGenre_retornaOk_quandoPassaOsDados()
    {
        $response = $this->getJson('api/genre');
        $response->assertStatus(201);
    }

    /**
    * @return void
    */
    public function test_buscarStreaming_retornaOk_quandoPassaOsDados()
    {
        $response = $this->getJson('api/streaming');
        $response->assertStatus(200);
    }

    /**
    * @return void
    */
    public function test_buscarUser_retornaOk_quandoPassaOsDados()
    {
        $response = $this->getJson('api/user');
        $response->assertStatus(200);
    }

    /**
    * @return void
    */
    public function test_buscarMovie_retornaOk_quandoPassaOsDados()
    {
        $response = $this->getJson('api/movies');
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testRateMovie()
    {
        // Criar um usuário e um filme para testar
        $user = factory(User::class)->create();
        $movie = factory(Movie::class)->create();

        $response = $this->post("api/movies/{$movie->id}/rate", [
            'user_id' => $user->id,
            'rating' => 4,
            'comment' => 'Ótimo filme!',
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('assessments', [
            'user_id' => $user->id,
            'rating' => 4,
            'comment' => 'Ótimo filme!',
        ]);
    }

    /**
     * @return void
     */
    public function testAssociateStreamings()
    {
        // Criar um filme e algumas plataformas de streaming
        $movie = factory(Movie::class)->create();
        $streamings = factory(Streaming::class, 3)->create();

        
        $response = $this->post("api/movies/{$movie->id}/associate-streamings", [
            'streaming_id' => $streamings->pluck('id')->toArray(),
        ]);
        $response->assertStatus(200);
        foreach ($streamings as $streaming) {
            $this->assertDatabaseHas('movie_streaming', [
                'movie_id' => $movie->id,
                'streaming_id' => $streaming->id,
            ]);
        }
    }

    /**
     * @return void
     */
    public function testStreamingCount()
    {
        // Criar um filme e algumas plataformas de streaming associadas
        $movie = factory(Movie::class)->create();
        $streamings = factory(Streaming::class, 3)->create();
        $movie->streamings()->sync($streamings->pluck('id')->toArray());

        $response = $this->get("api/movies/{$movie->id}/streaming-count");
        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertEquals(count($streamings), $responseData['streaming_count']);
    }

    /**
     * @return void
     */
    public function testAverageRating()
    {
        // Criar alguns filmes com avaliações
        $movies = factory(Movie::class, 3)->create();
        $movies->each(function ($movie) {
            factory(Assessment::class, 5)->create(['movie_id' => $movie->id]);
        });

        $response = $this->get('api/movies/average-rating');
        $response->assertStatus(200);
        $responseData = $response->json();
        foreach ($responseData as $movieData) {
            $this->assertArrayHasKey('movie_id', $movieData);
            $this->assertArrayHasKey('title', $movieData);
            $this->assertArrayHasKey('average_rating', $movieData);
        }
    }

    /**
     * @return void
     */
    public function testFindMoviesByRating()
    {
        // Criar alguns filmes com avaliações variadas
        $movies = factory(Movie::class, 3)->create();
        $movies->each(function ($movie) {
            factory(Assessment::class, 5)->create(['movie_id' => $movie->id]);
        });

        $response = $this->post('api/movies/by-rating', [
            'min_rating' => 3,
            'max_rating' => 4,
        ]);
        $response->assertStatus(200);
        $responseData = $response->json();
        foreach ($responseData as $movie) {
            $this->assertTrue($movie['assessment']->avg('rating') >= 3);
            $this->assertTrue($movie['assessment']->avg('rating') <= 4);
        }
    }

    /**
     * @return void
     */
    public function testMoviesByYear()
    {
        // Criar alguns filmes com anos de lançamento diferentes
        $movies = factory(Movie::class, 3)->create([
            'release_year' => now()->year,
        ]);
        $movies = factory(Movie::class, 2)->create([
            'release_year' => now()->year - 1,
        ]);

        $response = $this->get('api/movies/by-year');
        $response->assertStatus(200);
        $responseData = $response->json();
        foreach ($responseData as $yearData) {
            $this->assertArrayHasKey('year', $yearData);
            $this->assertArrayHasKey('movie_count', $yearData);
        }
        $this->assertEquals($movies->countBy('release_year')->toArray(), collect($responseData)->pluck('movie_count')->toArray());
    }

    /**
     * @return void
     */
    public function testAverageRatingsByGenreAndYear()
    {
        // Criar alguns filmes com avaliações e diferentes gêneros e anos
        $genres = factory(Genre::class, 3)->create();
        $movies = factory(Movie::class, 6)->create([
            'genre_id' => $genres->random()->id,
        ]);
        $movies->each(function ($movie) {
            factory(Assessment::class, rand(1, 5))->create(['movie_id' => $movie->id]);
        });

        $response = $this->get('api/movies/average-ratings-by-genre-and-year');
        $response->assertStatus(200);
        $responseData = $response->json();
        foreach ($responseData as $data) {
            $this->assertArrayHasKey('genre', $data);
            $this->assertArrayHasKey('release_month', $data);
            $this->assertArrayHasKey('release_year', $data);
            $this->assertArrayHasKey('average_rating', $data);
        }
    }
}
