<?php

namespace App\Http\Controllers\api;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{

    public function index()
    {
        $post=Post::all();
        return response()->json($post,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = Validator::make($request->all(), [
            'body'=>'required',
            'user_id'=>'required'
        ]);
        if ($data->fails()){
            return response()->json(['msg'=>$data->errors()],404);
        }
       $post= new Post($request->all());
        $post->save();
        return response()->json(['msg'=>'post added','data'=>$post],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post=Post::all()->find($id);
        if (empty($post)){
            return response()->json(['msg'=>'post not find'],404);
        }
        return response()->json($post,200);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post=Post::all()->find($id);
        if (empty($post)){
            return response()->json(['msg'=>'post not find'],404);
        }
        $post->update($request->all());
        return response()->json(['msg'=>'post update','data'=>$post],201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post=Post::all()->find($id);
        if (empty($post)){
            return response()->json(['msg'=>'post not find'],404);
        }
        $post->delete();
        return response()->json(['msg'=>'post deleted','data'=>$post],202);
    }
}
