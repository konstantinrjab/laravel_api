<?php

namespace App\Http\Controllers;

use App\CategoryParameter;
use App\Http\Requests\AddCategoryParameterRequest;
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
//        $handler = new AddCategoryParameterRequest();
//        $handler->add($categoryID, $request);

        $values = Input::all();
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'parameter_id' => 'required',
        ]);

        if ($validator->fails()) {
            return Error::getStructure(
                'Parameters are invalid or missing: ' . $validator->errors()
            );
        }
//        $validateErrors = $this->validateParameters(
//            $this::TABLE_NAME,
//            $values,
//            ['category_id' => 'categories']
//        );
//
//        $parameter = CategoryParameter::create($values);
//        return CategoryParameterStructure::getParameterStructure($parameter);

        return response()->json('ololo', 201);
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