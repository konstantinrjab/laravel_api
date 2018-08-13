<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

/** @SWG\Tag(
 *   name="categories",
 *   description="have products"
 * )
 *
 * @SWG\Definition(
 *   definition="category",
 *   @SWG\Property(
 *      property="id",
 *      type="integer",
 *      description="Category ID"
 *   ),
 *   @SWG\Property(
 *      property="name",
 *      type="string",
 *      description="Category Name"
 *   ),
 *   @SWG\Property(
 *      property="created_at",
 *      type="string",
 *   ),
 *   @SWG\Property(
 *      property="updated_at",
 *      type="string",
 *   ),
 * )
 */
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
     *              @SWG\Schema(
     *                  @SWG\Property(
     *                  property="category",
     *                  ref="#definitions/category"),
     *              ),
     *          response=400,
     *          description="Bad request"),
     *     )
     *
     * Returns list of projects
     */
    public function index()
    {
        return Category::all();
    }
    
    public function show(Category $category)
    {
        header("Access-Control-Allow-Origin:*");
    
        return $category;
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
    
    public function getWithItems($id)
    {
        return Category::with(['items'])->find($id);
    }
}
