<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        return CategoryResource::collection(Category::all());
    }
}
