<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::paginate(10);
        if ($user->isEmpty()) {
            $user = 'Não foi encontrado nenhum registro.';
        }
        return $user;
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
        $user = User::create($data);
        return $user->id;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);
        return  response()->json($user, 200);
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
        $user = User::findOrFail($id);
        $update = $user->update($data);
        return response()->json($update, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }
}
