<?php

namespace App\Http\Controllers;


use App\Category;
use App\CategoryParameter;

class CategoryParametersController
{

    public function index()
    {
        $parameters = CategoryParameter::all();
        
        return $parameters;
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
     *          description="Bad request"),
     *     )
     *
     * Returns list of projects
     */
    public function getByCategory($id)
    {
        $parameters = CategoryParameter::where(['category_id' => $id])->all();
        return $parameters;
    }
    
    public function show(CategoryParameter $categryParameter)
    {
        return $categryParameter;
    }
    
    public function store(Request $request)
    {
        $parameter = Parameter::create($request->all());
        
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