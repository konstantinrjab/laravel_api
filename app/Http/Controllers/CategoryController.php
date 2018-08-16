<?php

namespace App\Http\Controllers;

use App\Http\Structures\Category as CategoryStructure;
use App\Http\Structures\Error;
use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Facades\Input;


class CategoryController extends Controller
{
    /**
     * @SWG\Get(
     *      path="/categories",
     *      operationId="getCategpriesList",
     *      tags={"categories"},
     *      summary="Get list of categories",
     *      description="Returns list of categories",
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="count",
     *                  type="integer",
     *              ),
     *              @SWG\Property(
     *                  property="categories",
     *                  type="array",
     *                  @SWG\Items(ref="#definitions/category")
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(
     *          response=400,
     *          description="Bad request"),
     *     )
     *
     * Returns list of projects
     */
    public function index()
    {
        $categories = Category::all();
        
        return CategoryStructure::getCategoriesStructure($categories, count($categories));
    }
    
    public function show($id)
    {
        if(Input::get('items')){
            $category = Category::with('items')->find($id);
            $items_count = count($category->items);
            $items = $category->items;
        } else {
            $category = Category::withCount('items')->find($id);
            $items_count = $category->items_count;
            $items = null;
        }

        return CategoryStructure::getCategoryStructure($category, $items_count, $items);
    }
    
    public function store(Request $request)
    {
        if (!$request->name) {
            $error = Error::getStructure('name required');
            return response()->json($error, 400);
        }
        if (Category::where(['name' => $request->name])->first()) {
            $error = Error::getStructure('name is busy');
            return response()->json($error, 409);
        }
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
    
    public function getWithItems($id)
    {
        return Category::with(['items'])->find($id);
    }
}
