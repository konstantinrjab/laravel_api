<?php

namespace App\Http\Controllers;

use App\ItemParameter;
use App\Parameter;
use Illuminate\Http\Request;
use App\Http\Structures\Error;
use Illuminate\Database\QueryException;


class ItemParametersController extends Controller
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
        
        return $parameters;
    }
    
    public function show(ItemParameter $itemParameter)
    {
        return $itemParameter;
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
            $category = Parameter::create($values);
            return ParameterStructure::getCategoryStructure($category);
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
