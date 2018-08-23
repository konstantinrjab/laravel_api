<?php

namespace App\Http\Controllers;

use App\Http\Structures\Category as CategoryStructure;
use App\Http\Structures\Error;
use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\QueryException;
use Validator;


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

        return CategoryStructure::getMany($categories);
    }

    public function show($id)
    {
        if (Input::get('items')) {
            $category = Category::with('items')->find($id);
            $items = $category->items;
        } else {
            $category = Category::withCount('items')->find($id);
            $items = null;
        }

        return CategoryStructure::getOne($category, $items);
    }

    public function store(Request $request)
    {
        $values = [
            'name' => $request->name,
        ];

        $validator = Validator::make($values, [
            'name' => 'required|unique:categories,name',
        ]);

        if ($validator->fails()) {
            return Error::getStructure(
                $validator->errors()
            );
        }

        try {
            $category = Category::create($values);
            return CategoryStructure::getOne($category);
        } catch (QueryException $e) {
            return Error::getStructure('Unexpected error');
        }
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
