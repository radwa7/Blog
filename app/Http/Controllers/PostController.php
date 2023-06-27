<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function index(){

        $Allposts = Post::all();
        $posts = array();
        foreach ($Allposts as $post) {
            array_push($posts,$post);
        }
        return response()->json([
            'posts' => $posts
        ],200);
    }

    public function getPost($id){
        try {
            $post = Post::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'message' => 'post not found'
            ],404);
        } 
        return response()->json([
            'post' => $post
        ],200);
    }

    public function create(Request $request){
        $post = $request->validate([
            'title' => 'required|string|',Rule::unique('posts')->whereNull('deleted_at'),
            'content' => 'required',
            
        ]);

        $post = Post::create([
            'title' => $post['title'],
            'content' => $post['content'],
        ]);
        
        return response()->json([
            'post' => $post
        ],201);
    }

    public function update(Request $request,$id){
        try {
            $post = Post::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'message' => 'post not found'
            ],404);
        } 
        $newPost = $request->validate([
            'title' => 'required|unique:posts,title,'.$post->id,
            'content' => 'required',
        ]);
        $post->update($newPost);
        return response()->json([
            'post' => $post
        ],200);

    }

    public function delete($id){
        try {
            $post = Post::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'message' => 'post does not exist'
            ],404);
        } 
        $post->delete();
        return response()->json([
            'message' => 'post deleted'
        ],200);
    }


    public function search($scope,$keyword){
        $Allposts = Post::where($scope,'like','%'.$keyword.'%')->get();
        $posts = array();
        foreach ($Allposts as $post) {
            array_push($posts,$post);
        }
        if(empty($posts)) {
            return response()->json([
                'message'=> 'no posts found'
            ],404); 
        }else{
            return response()->json([
                'posts'=> $posts
            ],200);
        }
    }
}
