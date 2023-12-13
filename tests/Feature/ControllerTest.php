<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\Assessment;
use App\Models\User;
use App\Models\Streaming;
use Illuminate\Foundation\Testing\WithFaker;
use Database\Factories\UserFactory;
use Database\Factories\MovieFactory;
use Database\Factories\StreamingFactory;
use Database\Factories\GenreFactory;

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
    }

    /**
    * @return void
    */
    public function test_criarMovie_retornaOk_quandoPassaOsDados()
    {
        $genre = Genre::first();
        $response = $this->postJson('api/movies', [
            "title" => "Filme titulo",
            "release_month" => "12",
            "release_year" => "2023",
            "genre_id" => $genre->id
        ]);
        $response->assertStatus(200);
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
        $user = UserFactory::new();
        $movie = MovieFactory::new();

        $movieId = Movie::first();
        $userId = User::first();

        $response = $this->post("api/movies/{$movieId->id}/rate", [
            'user_id' => $userId ,
            'rating' => 4,
            'comment' => 'Ótimo filme!',
        ]);
        $response->assertStatus(201);
    }

    /**
     * @return void
     */
    public function testAssociateStreamings()
    {
        // Criar um filme e algumas plataformas de streaming
        $movie = MovieFactory::new();
        $streamings = StreamingFactory::new();

        $movieId = Movie::first();
        dd($movieId);
        $response = $this->post("api/movies/{$movieId->id}/associate-streamings", [
            'streaming_id' => $streamings->pluck('id')->toArray(),
        ]);
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testStreamingCount()
    {
        // Criar um filme e algumas plataformas de streaming associadas
        $movie = MovieFactory::new();
        $streamings =  $streamings = StreamingFactory::new();
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
        $movie = MovieFactory::new();
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
        $movie = MovieFactory::new();
        $response = $this->post('api/movies/by-rating', [
            'min_rating' => 3,
            'max_rating' => 4,
        ]);
        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testMoviesByYear()
    {
        // Criar alguns filmes com anos de lançamento diferentes
        $movie = MovieFactory::new();

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
        $genres = GenreFactory::new();
        

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
