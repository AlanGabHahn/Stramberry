<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Genre;
use Illuminate\Support\Facades\Validator;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collection = Genre::paginate(10);
        if ($collection->isEmpty()) {
            $collection = 'Não foi encontrado nenhum registro.';
        }
        return response()->json($collection, 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $rules = [
            'name' => [
                'required'
            ]
        ];
        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return $validate->messages();
        }
        $genre = Genre::create($data);
        return $genre->id;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $genre = Genre::findOrFail($id);
        return  response()->json($genre, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        $rules = [
            'name' => [
                'required'
            ]
        ];
        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return $validate->messages();
        }
        $genre = Genre::findOrFail($id);
        $update = $genre->update($data);
        return response()->json($update, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $genre = Genre::findOrFail($id);
        return $genre->delete();
    }
}
