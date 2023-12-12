<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\Assessment;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $collection = Genre::with('movies')->paginate(10);
        return response()->json($collection);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $rules = [
            'title' => 'required|unique:movies,title',
            'release_month' => 'required|numeric|max:12',
            'release_year' => 'required|numeric|max:4',
            'genre_id' => 'required|exists:genres,id'
        ];
        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return $validate->messages();
        }
        $movie = Movie::create($data);
        return $movie->id;  
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Movie::findOrFail($id);
        return response()->json($data, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {   
        $data = $request->all();
        $rules = [
            'title' => 'required|unique:movies,title',
            'release_month' => 'required|numeric|max:12',
            'release_year' => 'required|numeric|max:4',
            'genre_id' => 'required|exists:genres,id'
        ];
        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return $validate->messages();
        }
        $movie = Movie::findOrFail($id);
        $update = $movie->update($data);
        return response()->json($update, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Movie::findOrFail($id);
        return $data->delete();
    }

    public function rateMovie(Request $request, $id)
    {   
        $data = $request->all();
        $rules = [
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string',
        ];
        $movie = Movie::findOrFail($id);
        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return $validate->messages();
        }
        $rating = new Assessment([
            'user_id' => $request->input('user_id'),
            'rating' => $request->input('rating'),
            'comment' => $request->input('comment'),
        ]);
        $movie->assessment()->save($rating);
        return response()->json($rating, 201);
    }

    public function associateStreamings(Request $request, $id)
    {   
        $data = $request->all();
        $rules =[
            'streaming_id' => 'required|array',
            'streaming_id.*' => 'exists:streamings,id',
        ];
        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return $validate->messages();
        }
        $movie = Movie::findOrFail($id);
        $movie->streamings()->sync($request->input('streaming_id'));

        return response()->json($movie->load('streamings'), 200);
    }

    public function streamingCount($id)
    {
        $movie = Movie::findOrFail($id);
        $streamingCount = $movie->streamings()->count();
        return response()->json(['streaming_count' => $streamingCount], 200);
    }

    public function averageRating()
    {
        $movies = Movie::with('assessment')->get();
        $moviesWithAverageRating = $movies->map(function ($movie) {
            $averageRating = $movie->assessment->avg('rating');
            return [
                'movie_id' => $movie->id,
                'title' => $movie->title,
                'average_rating' => $averageRating,
            ];
        });
        return response()->json($moviesWithAverageRating, 200);
    }

    public function findMoviesByRating(Request $request)
    {   
        $data = $request->all();
        $rules = [
            'min_rating' => 'required|numeric|min:1|max:5',
            'max_rating' => 'required|numeric|min:1|max:5|gte:min_rating',
        ];
        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return $validate->messages();
        }
        $minRating = $request->input('min_rating');
        $maxRating = $request->input('max_rating');

        $movies = Movie::whereHas('assessment', function ($query) use ($minRating, $maxRating) {
            $query->whereBetween('rating', [$minRating, $maxRating]);
        })->with(['assessment' => function ($query) use ($minRating, $maxRating) {
            $query->whereBetween('rating', [$minRating, $maxRating]);
        }])->get();

        return response()->json($movies, 200);
    }

    public function moviesByYear()
    {
        $moviesByYear = Movie::select(
            DB::raw('YEAR(release_year) as year'),
            DB::raw('COUNT(*) as movie_count')
        )
        ->groupBy(DB::raw('YEAR(release_year)'))
        ->orderBy(DB::raw('YEAR(release_year)'))
        ->get();
        return response()->json($moviesByYear, 200);
    }

    public function averageRatingsByGenreAndYear()
    {
        $collection = Movie::select(
            'genres.name as genre',
            'movies.release_month',
            'movies.release_year',
            DB::raw('AVG(assessments.rating) as average_rating')
        )
        ->join('genres', 'movies.genre_id', '=', 'genres.id')
        ->leftJoin('assessments', 'movies.id', '=', 'assessments.movie_id')
        ->groupBy('genres.name', 'movies.release_month', 'movies.release_year')
        ->orderBy('genres.name')
        ->orderBy('movies.release_year')
        ->orderBy('movies.release_month')
        ->get();
        return response()->json($collection, 200);
    }
 
}
