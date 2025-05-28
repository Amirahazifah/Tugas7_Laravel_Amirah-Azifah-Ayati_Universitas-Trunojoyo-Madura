<?php

namespace App\Http\Controllers;

use App\Models\GenreModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use function Pest\Laravel\json;

class GenreController extends Controller
{
    public function index(){
        $genres = GenreModel ::all();

    
        if ($genres ->isEmpty()){
            return response() ->json([
                'success' => true,
                'message' => 'Resource data not found!'
            ]);
            }

    return response() ->json([
        "success" => true,
        "message" => "Get All Resource",
        "data"    => $genres
    ],200);
    }
    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'description' => 'required|string',
        ]);
    
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 422);
        }
    
        $genre = GenreModel::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);
    
        return response()->json([
            'success' => true,
            'message' => 'Resource added successfully',
            'data' => $genre
        ], 201);
    
    }
    public function show (string $id){
        $genre = GenreModel::find($id);

        if (!$genre) {
            return response() -> json([
                'success' => false,
                'message' => 'Resource not found'
            ],404);
        }
        return response() ->json([
            'success' => true,
            'message' => 'Get detail resource',
            'data' => $genre
        ],200);
    }
    public function update(Request $request,string $id) {
    
        $genre = GenreModel::find($id);
        
        if (!$genre) {
            return response() ->json([
                'success' => false,
                'message' => 'Resource data not found!',
                'data' => null
            ],404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'description' => 'required|string',
        ]);

        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ],422);
        }

        $genre ->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Resource added successfully',
            'data' => $genre
        ], 200);
    }
    public function destroy(string $id){
        $genre = GenreModel::find($id);
    
        if (!$genre) {
            return response()->json([
                'success' => false,
                'message' => 'Resource data not found!',
                'data' => null
            ], 404);
        }
    
        $genre->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Resource deleted successfully'
        ], 200);
    }
}
