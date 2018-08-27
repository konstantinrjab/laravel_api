<?php

namespace App\Http\Controllers;

use App\Parameter;
use Illuminate\Http\Request;
use App\Http\Structures\Error;
use Illuminate\Database\QueryException;
use App\Http\Structures\Parameter as ParameterStructure;
use Validator;


class ParameterController extends Controller
{
    /**
     * @SWG\Get(
     *      path="/parameters",
     *      operationId="getParametersList",
     *      tags={"parameters"},
     *      summary="Get list of parameters",
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
     *                  @SWG\Items(ref="#definitions/parameter")
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
        $parameters = Parameter::all();
        return ParameterStructure::getMany($parameters);
    }

    public function show($parameterID)
    {
        $parameter = Parameter::find($parameterID);
        return ParameterStructure::getOne($parameter);
    }

    public function store(Request $request)
    {
        $values = [
            'name' => $request->name,
        ];

        $validator = Validator::make($values, [
            'name' => 'required|unique:parameters,name',
        ]);

        if ($validator->fails()) {
            return Error::getStructure(
                $validator->errors()
            );
        }

        try {
            $parameter = Parameter::create($values);
            return ParameterStructure::getOne($parameter);
        } catch (QueryException $e) {
            return Error::getStructure('Unexpected error');
        }
    }

    public function update(Request $request, Parameter $parameter)
    {
        $parameter->update($request->all());

        return response()->json($parameter, 200);
    }

    public function delete($parameterID)
    {
        return $this->deleteIdentByID($parameterID, '\App\Parameter');
    }
}
