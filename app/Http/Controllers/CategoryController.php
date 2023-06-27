<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    
    public function index(){

        $categories = Category::all();
        $cat = array();
        foreach ($categories as $category) {
            array_push($cat,$category);
        }
        return response()->json([
            'categories' => $cat
        ],200);
    }

    public function getCategory($id){
        try {
            $cat = Category::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'message' => 'category not found'
            ],404);
        } 
        return response()->json([
            'category' => $cat
        ],200);
    }

    public function create(Request $request){
        $cat = $request->validate([
            'name' =>'required|string',
            'name' => Rule::unique('categories','name')->whereNull('deleted_at'),

        ]);

        $cat = Category::create([
            'name' => $cat['name']
        ]);
        
        return response()->json([
            'category' => $cat
        ],201);
    }

    public function update(Request $request,$id){
        try {
            $cat = Category::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'message' => 'category not found'
            ],404);
        } 
        $newCat = $request->validate([
            'name' => 'required|string|unique:categories,name,'.$cat->id
        ]);
        $cat->update($newCat);
        return response()->json([
            'category' => $cat
        ],200);

    }

    public function delete($id){
        try {
            $cat = Category::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            return response()->json([
                'status' => false,
                'message' => 'category does not exist'
            ],404);
        } 
        $cat->delete();
        return response()->json([
            'message' => 'category deleted'
        ],200);
    }


    
    
}
