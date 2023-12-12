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
   /**
    * @return void
    */
    public function test_criarGenre_retornaOk_quandoPassaOsDados()
    {
        $response = $this->postJson('api/genre', [
            "name" => "Gênero Teste"
        ]);
        $response->assertStatus(201);
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
        $response->assertStatus(201);
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
        $response->assertStatus(201);
        $_SESSION['user_id_test'] = $response['id'];
    }

    /**
    * @return void
    */
    public function test_criarMovie_retornaOk_quandoPassaOsDados()
    {
        $response = $this->postJson('api/movies', [
            "title" => "Filme titulo"
            "release_month" => "12",
            "release_year" => "2023",
            "genre_id" => $_SESSION['genre_id_test']
        ]);
        $response->assertStatus(201);
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
        $response->assertStatus(201);
    }

    /**
    * @return void
    */
    public function test_buscarUser_retornaOk_quandoPassaOsDados()
    {
        $response = $this->getJson('api/user');
        $response->assertStatus(201);
    }

    /**
    * @return void
    */
    public function test_buscarMovie_retornaOk_quandoPassaOsDados()
    {
        $response = $this->getJson('api/movies');
        $response->assertStatus(201);
    }
}
