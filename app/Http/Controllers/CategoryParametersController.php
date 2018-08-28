<?php

namespace App\Http\Controllers;

use App\CategoryParameter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use App\Http\Structures\CategoryParameter as CategoryParameterStructure;
use App\Http\Structures\Error;
use Illuminate\Http\Request;
use Validator;

class CategoryParametersController extends Controller
{
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
            'category_id' => 'required|exists:categories,id',
            'parameter_id' => 'required|exists:parameters,id',
        ];
    }

    public function index()
    {
        $parameters = CategoryParameter::all();
        return CategoryParameterStructure::getMany($parameters);
    }

    /**
     * @SWG\Get(
     *      path="/category/{categoryID}/parameters",
     *      operationId="getCategoryParametersList",
     *      tags={"parameters, category"},
     *      summary="Get category parameters",
     *      description="Returns list of parameters",
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="count",
     *                  type="integer",
     *              ),
     *              @SWG\Property(
     *                  property="parameters",
     *                  type="array",
     *                  @SWG\Items(ref="#definitions/categoryParameter")
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(
     *          response=400,
     *          description="Bad request"
     *      ),
     * )
     *
     * @param $id integer
     * @return array
     * Returns list of objects
     */
    public function getByCategory($categoryID)
    {
        $parameters = CategoryParameter::where(['category_id' => $categoryID])->get();
        return CategoryParameterStructure::getMany($parameters);
    }

    public function show($categoryParameterID)
    {
        $parameter = CategoryParameter::find($categoryParameterID);
        if (is_null($parameter)) {
            throw new ModelNotFoundException();
        }
        return CategoryParameterStructure::getOne($parameter);
    }

    public function store($categoryID, Request $request)
    {
        $values = $this->_getRequestValues($request);
        $values['category_id'] = $categoryID;
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

    public function update(Request $request, $parameterID)
    {
        $values = $this->_getRequestValues($request);
        $rules = $this->getUpdateRules();

        $validator = Validator::make($values, $rules);

        if ($validator->fails()) {
            return Error::getStructure(
                $validator->errors()
            );
        }
        $parameter = CategoryParameter::find($parameterID);
        $parameter->update($values);

        return response()->json(CategoryParameterStructure::getOne($parameter), 200);
    }

    public function delete($parameterID)
    {
        return $this->deleteIdentByID($parameterID, '\App\CategoryParameter');
    }
}