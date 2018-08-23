<?php

namespace App\Http\Controllers;

use App\CategoryParameter;
use Illuminate\Database\QueryException;
use App\Http\Structures\CategoryParameter as CategoryParameterStructure;
use App\Http\Structures\Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CategoryParametersController extends Controller
{
    const TABLE_NAME = 'category_parameter';

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

    public function index()
    {
        $parameters = CategoryParameter::all();
        return CategoryParameterStructure::getParametersStructure($parameters);
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
    public function getByCategory($id)
    {
        $parameters = CategoryParameter::where(['category_id' => $id])->get();
        return CategoryParameterStructure::getParametersStructure($parameters);
    }

    public function show($id)
    {
        $parameter = CategoryParameter::find($id);
        return CategoryParameterStructure::getParameterStructure($parameter);
    }

    public function store($categoryID, Request $request)
    {
        $values = [
            'category_id' => $categoryID,
            'parameter_id' => $request->parameter_id
        ];

        $validator = Validator::make($values, [
            'category_id' => ['required', 'exists:categories,id'],
            'parameter_id' => ['required', 'exists:parameters,id'],
        ]);

        if ($validator->fails()) {
            return Error::getStructure(
                $validator->errors()
            );
        }
        try {
            $parameter = CategoryParameter::create($values);
            return CategoryParameterStructure::getParameterStructure($parameter);
        } catch (QueryException $e) {
            return Error::getStructure('Unexpected error');
        }
    }

    public function update(Request $request, Parameter $parameter)
    {
        $parameter->update($request->all());

        return response()->json($parameter, 200);
    }

    public function delete(Parameter $parameter)
    {
        $parameter->delete();

        return response()->json(null, 204);
    }
}