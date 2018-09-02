<?php

namespace App\Http\Controllers;

use App\CategoryParameter;
use Illuminate\Database\QueryException;
use App\Http\Structures\CategoryParameter as CategoryParameterStructure;
use App\Http\Structures\Error;
use Illuminate\Http\Request;
use Validator;

class CategoryParametersController extends Controller
{
    const TABLE_NAME = 'category_parameter';

    private function _getRequestValues($request)
    {
        return [
            'category_id' => $request->category_id,
            'parameter_id' => $request->parameter_id
        ];
    }

    protected function getRules()
    {
        return [
            'category_id' => 'required|integer|exists:categories,id',
            'parameter_id' => 'required|integer|exists:parameters,id',
        ];
    }

    /**
     * @SWG\Get(
     *      path="/categories/parameters/",
     *      tags={"categoryParameter"},
     *      summary="Get list of categoryParameters",
     *      description="Returns list of categoryParameters",
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="parameters",
     *                  type="array",
     *                  @SWG\Items(ref="#definitions/categoryParameter")
     *              ),
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
        $parameters = CategoryParameter::all();
        return CategoryParameterStructure::getMany($parameters);
    }

    /**
     * @SWG\Get(
     *      path="/categories/{categoryID}/parameters/",
     *      tags={"categoryParameter"},
     *      summary="Get category parameters by categoryID",
     *      description="Returns list of parameters",
     *      @SWG\Parameter(
     *           name="categoryID",
     *           in="path",
     *           description="Category ID",
     *           required=true,
     *           type="integer",
     *           @SWG\Schema(
     *              example="1"
     *          ),
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="parameters",
     *                  type="array",
     *                  @SWG\Items(ref="#definitions/categoryParameter")
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(
     *          response="default",
     *          description="Error",
     *      ),
     * )
     *
     * @param $categoryID integer
     * @return array
     * Returns list of parameters
     */
    public function getByCategory($categoryID)
    {
        $this->existOrDie(CategoryController::TABLE_NAME, $categoryID);
        $parameters = CategoryParameter::where(['category_id' => $categoryID])->get();
        return CategoryParameterStructure::getMany($parameters);
    }

    /**
     * @SWG\Get(
     *      path="/categories/parameters/{categoryParameterID}/",
     *      tags={"categoryParameter"},
     *      summary="Get categoryParameter",
     *      description="Returns categoryParameter",
     *      @SWG\Parameter(
     *           name="categoryParameterID",
     *           in="path",
     *           required=true,
     *           type="integer",
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="parameter",
     *                  ref="#definitions/categoryParameter"
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(
     *          response="default",
     *          description="Error",
     *     )
     *  )
     *
     * Returns item
     */
    public function show($categoryParameterID)
    {
        $this->existOrDie($this::TABLE_NAME, $categoryParameterID);
        $parameter = CategoryParameter::find($categoryParameterID);

        return CategoryParameterStructure::getOne($parameter);
    }

    /**
     * @SWG\Post(
     *      path="/categories/parameters/",
     *      tags={"categoryParameter"},
     *      summary="Add categoryParameter",
     *      @SWG\Parameter(
     *          in="formData",
     *          name="category_id",
     *          type="integer",
     *          required=true,
     *          @SWG\Schema(
     *              example="1"
     *          ),
     *      ),
     *      @SWG\Parameter(
     *          in="formData",
     *          name="parameter_id",
     *          type="integer",
     *          required=true,
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="item",
     *                  type="object",
     *                  ref="#definitions/categoryParameter"
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(
     *          response="default",
     *          description="Error",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="error",
     *                  type="object",
     *                  ref="#definitions/error"
     *              ),
     *          )
     *     ),
     *     security={{"api_key":{}}}
     *  )
     *
     * Add categoryParameter
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
            $parameter = CategoryParameter::create($values);
            return CategoryParameterStructure::getOne($parameter);
        } catch (QueryException $e) {
            return Error::getStructure('Unexpected error');
        }
    }

    /**
     * @SWG\Delete(
     *      path="/categories/parameters/{parameterID}/",
     *      tags={"categoryParameter"},
     *      summary="Delete categoryParameter",
     *      @SWG\Parameter(
     *          in="path",
     *          name="parameterID",
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
     *              @SWG\Property(
     *                  property="error",
     *                  type="object",
     *                  ref="#definitions/error"
     *              ),
     *          )
     *     ),
     *     security={{"api_key":{}}}
     *  )
     *
     * Delete item
     */
    public function delete($parameterID)
    {
        $this->existOrDie($this::TABLE_NAME, $parameterID);

        return $this->deleteIdentByID($parameterID, '\App\CategoryParameter');
    }
}