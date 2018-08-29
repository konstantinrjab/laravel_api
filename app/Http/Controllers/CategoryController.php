<?php

namespace App\Http\Controllers;

use App\Http\Structures\Category as CategoryStructure;
use App\Http\Structures\Error;
use App\Item;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\QueryException;
use Validator;
use Illuminate\Support\Facades\DB;


class CategoryController extends Controller
{
    const NAME_UNCATEGORIZED = 'Uncategorized';

    private function _getRequestValues($request)
    {
        return [
            'name' => $request->name,
        ];
    }

    protected function getRules()
    {
        return [
            'name' => 'required|unique:categories,name',
        ];
    }

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

    public function show($categoryID)
    {
        if (Input::get('items')) {
            $category = Category::with('items')->find($categoryID);
            $items = $category->items;
        } else {
            $category = Category::withCount('items')->find($categoryID);
            $items = null;
        }
        if (is_null($category)) {
            throw new ModelNotFoundException();
        }

        return CategoryStructure::getOne($category, $items);
    }

    public function store(Request $request)
    {
        $values = $this->_getRequestValues($request);
        $rules = $this->getRules();
        $validator = Validator::make($values, $rules);

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

    public function update(Request $request, $categoryID)
    {
        $values = $this->_getRequestValues($request);
        $rules = $this->getUpdateRules();

        $validator = Validator::make($values, $rules);

        if ($validator->fails()) {
            return Error::getStructure(
                $validator->errors()
            );
        }
        $category = Category::find($categoryID);
        $category->update($values);

        return response()->json(CategoryStructure::getOne($category), 200);
    }

    //add desc: when deleted, items moved to Uncategorized
    public function delete($categoryID)
    {
        $uncategorized = Category::where('name', $this::NAME_UNCATEGORIZED)->first();
        if (is_null($uncategorized)) {
            return response()->json(
                'Category for uncategorized items: "' . $this::NAME_UNCATEGORIZED . '" not found',
                404
            );
        }
        if ($categoryID == $uncategorized->id) {
            return response()->json(
                Error::getStructure('Cant delete default uncategorized category: ' . $this::NAME_UNCATEGORIZED),
                400
            );
        }
        DB::beginTransaction();
        try {
            Item::where('category_id', $categoryID)->update(['category_id' => $uncategorized->id]);
            $result = $this->deleteIdentByID($categoryID, '\App\Category');
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            return Error::getStructure('Unexpected error');
        }

    }

    public function getWithItems($id)
    {
        return Category::with(['items'])->find($id);
    }
}
