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
    const TABLE_NAME = 'categories';
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
     *      tags={"category"},
     *      summary="Get list of categories",
     *      description="Returns list of categories",
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              @SWG\Items(ref="#definitions/category")
     *          ),
     *     ),
     *     @SWG\Response(
     *          response="default",
     *          description="Error",
     *     ),
     * )
     *
     * Returns list of projects
     */
    public function index()
    {
        $categories = Category::all();

        return CategoryStructure::getMany($categories);
    }

    /**
     * @SWG\Get(
     *      path="/categories/{categoryID}",
     *      tags={"category"},
     *      summary="Get category",
     *      @SWG\Parameter(
     *          in="path",
     *          name="categoryID",
     *          required=true,
     *          type="integer",
     *          @SWG\Schema(
     *              example="1"
     *          ),
     *      ),
     *      @SWG\Parameter(
     *          in="query",
     *          name="items",
     *          type="boolean",
     *          @SWG\Schema(
     *              example="true"
     *          ),
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              ref="#definitions/category"
     *          ),
     *      ),
     *      @SWG\Response(
     *          response="default",
     *          description="Error",
     *          @SWG\Schema(
     *              ref="#definitions/error"
     *          )
     *      ),
     *  )
     *
     * Get Category
     */
    public function show($categoryID)
    {
        $this->existOrDie($this::TABLE_NAME, $categoryID);
        $category = Category::with('items')->find($categoryID);

        return CategoryStructure::getOne($category, (Input::get('items') == 'true'));
    }

    /**
     * @SWG\Post(
     *      path="/categories",
     *      tags={"category"},
     *      summary="Create new category",
     *      @SWG\Parameter(
     *          in="formData",
     *          name="name",
     *          required=true,
     *          type="string",
     *          @SWG\Schema(
     *              example="test name 1"
     *          ),
     *     ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *              @SWG\Schema(
     *                  ref="#definitions/category"
     *              ),
     *          ),
     *     @SWG\Response(
     *          response="default",
     *          description="Error",
     *          @SWG\Schema(
     *              ref="#definitions/error"
     *          )
     *     ),
     *     security={{"api_key":{}}}
     *  )
     *
     * Add Category
     */
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

    /**
     * @SWG\Post(
     *      path="/categories/{categoryID}",
     *      tags={"category"},
     *      summary="Update category",
     *      @SWG\Parameter(
     *          in="path",
     *          name="categoryID",
     *          required=true,
     *          type="integer",
     *          @SWG\Schema(
     *              example="1"
     *          ),
     *      ),
     *      @SWG\Parameter(
     *          in="formData",
     *          name="name",
     *          required=true,
     *          type="string",
     *          @SWG\Schema(
     *              example="test name 1"
     *          ),
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *              @SWG\Schema(
     *                  @SWG\Property(
     *                      property="category",
     *                      type="object",
     *                      ref="#definitions/category"
     *                  ),
     *              ),
     *          ),
     *     @SWG\Response(
     *          response="default",
     *          description="Error",
     *          @SWG\Schema(
     *              ref="#definitions/error"
     *          )
     *     ),
     *     security={{"api_key":{}}}
     *  )
     *
     * Update category
     */
    public function update(Request $request, $categoryID)
    {
        $category = Category::find($categoryID);
        if (is_null($category)) {
            throw new ModelNotFoundException();
        }
        $values = $this->_getRequestValues($request);
        $rules = $this->getUpdateRules();

        $validator = Validator::make($values, $rules);

        if ($validator->fails()) {
            return Error::getStructure(
                $validator->errors()
            );
        }
        $category->update($values);

        return response()->json(CategoryStructure::getOne($category), 200);
    }

    /**
     * @SWG\Delete(
     *      path="/categories/{categoryID}",
     *      tags={"category"},
     *      summary="Delete category",
     *      description="Items from delete category moves to category: Uncategorized",
     *      @SWG\Parameter(
     *          in="path",
     *          name="categoryID",
     *          required=true,
     *          type="integer",
     *          @SWG\Schema(
     *              example="1"
     *          ),
     *     ),
     *     @SWG\Response(
     *          response=204,
     *          description="successful operation",
     *     ),
     *     @SWG\Response(
     *          response="default",
     *          description="Error",
     *          @SWG\Schema(
     *              ref="#definitions/error"
     *          )
     *     ),
     *     security={{"api_key":{}}}
     *  )
     *
     * Delete category
     */
    public function delete($categoryID)
    {
        $this->existOrDie($this::TABLE_NAME, $categoryID);

        $uncategorized = Category::where('name', $this::NAME_UNCATEGORIZED)->first();
        if (is_null($uncategorized)) {
            return response()->json(
                'Category for uncategorized items: "' . $this::NAME_UNCATEGORIZED . '" not found',
                404
            );
        }
        if ($categoryID == $uncategorized->id) {
            return Error::getStructure(
                'Cant delete default uncategorized category: ' . $this::NAME_UNCATEGORIZED,
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
}
