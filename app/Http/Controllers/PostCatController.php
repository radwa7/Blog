<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\PostCat;
use Illuminate\Http\Request;

class PostCatController extends Controller
{
    public function assign(Request $request){
        $request->validate([
            'post' =>'exists:posts,id',
            'categories.*' => 'exists:categories,id'
        ]);
        foreach ($request->categories as $category) {
            $temp = PostCat::where('post_id',$request['post'])->where('category_id',$category)->get();
            if (count($temp)>0) {
                return response()->json([
                    'status' => false,
                    'message' => 'category '.$category .' is already assined to post'
                ],422);
            }
        }
        foreach($request->categories as $category){
            $postcat = PostCat::create([
                'post_id' => $request['post'],
                'category_id' => $category
            ]);
        }
        $categories = array();
        $post = Post::where('id',$request['post'])->first();
        $postCats = PostCat::where('post_id',$post->id)->get() ;
        foreach ($postCats as $category) {
            $cat = Category::where('id',$category->category_id)->first();
            array_push($categories,$cat->name);
        }
        $post['categories'] = $categories;

        return response()->json([
            'message' => 'cateogries assigned',
            'post'=>$post
        ],201);
    }

    public function unassign(Request $request){
        $request->validate([
            'post' =>'exists:posts,id',
            'categories.*' => 'exists:categories,id'
        ]);
        foreach ($request->categories as $category) {
            $temp = PostCat::where('post_id',$request['post'])->where('category_id',$category)->get();
            if (count($temp)== 0) {
                return response()->json([
                    'status' => false,
                    'message' => "category ".$category." isn't assined to post"
                ],422);
            }else{
                foreach ($temp as $record) { 
                    $record->delete();
                }
            }
        }
        $post = Post::where('id',$request['post'])->first();
        $postCats = PostCat::where('post_id',$post->id)->get() ;
        $categories = array();
        foreach ($postCats as $category) {
            $cat = Category::where('id',$category->category_id)->first();
            array_push($categories,$cat->name);
        }
        $post['categories'] = $categories;

        return response()->json([
            'message'=>'category removed from post',
            'post'=>$post
        ],201);
        
    }
}
