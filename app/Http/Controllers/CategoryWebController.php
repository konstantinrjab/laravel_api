<?php

namespace App\Http\Controllers;

use App\Http\Resources\Item;
use Illuminate\Http\Request;
use App\Category;

class CategoryWebController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        
        return view('categories', ['categories' => $categories]);
    }
    
    public function show(Category $category)
    {
        $controller = new ItemController;
        $items = $controller->index();
        return view('category', ['category' => $category, 'items' => $items]);
    }
    
    public function store(Request $request)
    {
        $category = Category::create($request->all());
        
        return response()->json($category, 201);
    }
    
    public function update(Request $request, Category $category)
    {
        $category->update($request->all());
        
        return response()->json($category, 200);
    }
    
    public function delete(Category $category)
    {
        $category->delete();
        
        return response()->json(null, 204);
    }
}
