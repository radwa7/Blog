<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostCatController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



//category CRUD routes
Route::get('categories',[CategoryController::class,'index']);
Route::get('category/{id}',[CategoryController::class,'getCategory']);
Route::post('createCategory',[CategoryController::class,'create']);
Route::put('updateCategory/{id}',[CategoryController::class,'update']);
Route::delete('deleteCategory/{id}',[CategoryController::class,'delete']);


//post CRUD routes
Route::get('posts',[PostController::class,'index']);
Route::get('post/{id}',[PostController::class,'getPost']);
Route::post('createPost',[PostController::class,'create']);
Route::put('updatePost/{id}',[PostController::class,'update']);
Route::delete('deletePost/{id}',[PostController::class,'delete']);


//assigning post to cateogry
Route::post('assignCat',[PostCatController::class,'assign']);
Route::post('unassignCat',[PostCatController::class,'unassign']);

//search for posts
Route::get('search/{scope}/{keyword}',[PostController::class,'search']);