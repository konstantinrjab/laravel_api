<?php

namespace App\Http\Controllers;


use App\CategoryParameter;
use App\Http\Structures\CategoryParameter as CategoryParameterStructure;

class CategoryParametersController
{

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
    
    public function store(Request $request)
    {
        $parameter = CategoryParameter::create($request->all());
        
        return response()->json($parameter, 201);
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