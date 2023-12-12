<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Streaming;
use Illuminate\Support\Facades\Validator;

class StreamingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $streaming = Streaming::paginate(10);
        if ($streaming->isEmpty()) {
            $streaming = 'Não foi encontrado nenhum registro.';
        }
        return $streaming;
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
        $streaming = Streaming::create($data);
        return $streaming->id;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $streaming = Streaming::findOrFail($id);
        return  response()->json($streaming, 200);
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
        $streaming = Streaming::findOrFail($id);
        $update = $streaming->update($data);
        return response()->json($update, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $streaming = Streaming::findOrFail($id);
        return $streaming->delete();
    }
}
