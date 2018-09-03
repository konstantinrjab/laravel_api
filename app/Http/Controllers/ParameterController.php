<?php

namespace App\Http\Controllers;

use App\Parameter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Structures\Error;
use Illuminate\Database\QueryException;
use App\Http\Structures\Parameter as ParameterStructure;
use Validator;


class ParameterController extends Controller
{
    const TABLE_NAME = 'parameters';

    private function _getRequestValues($request)
    {
        return [
            'name' => $request->name,
        ];
    }

    protected function getRules()
    {
        return [
            'name' => 'required|unique:parameters,name',
        ];
    }

    /**
     * @SWG\Get(
     *      path="/parameters/",
     *      tags={"parameter"},
     *      summary="Get list of parameters",
     *      description="Returns list of parameters",
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="parameters",
     *                  type="array",
     *                  @SWG\Items(ref="#definitions/parameter")
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
        $parameters = Parameter::all();
        return ParameterStructure::getMany($parameters);
    }

    public function show($parameterID)
    {
        $this->existOrDie($this::TABLE_NAME, $parameterID);

        $parameter = Parameter::find($parameterID);
        return ParameterStructure::getOne($parameter);
    }

    /**
     * @SWG\Post(
     *      path="/parameters/",
     *      tags={"parameter"},
     *      summary="Add parameter",
     *      @SWG\Parameter(
     *          in="formData",
     *          name="name",
     *          type="string",
     *          required=true,
     *          @SWG\Schema(
     *              example="test name"
     *          ),
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              ref="#definitions/parameter"
     *          ),
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
     * @param $request
     * @return mixed
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
            $parameter = Parameter::create($values);
            return ParameterStructure::getOne($parameter);
        } catch (QueryException $e) {
            return Error::getStructure('Unexpected error');
        }
    }

    /**
     * @SWG\Post(
     *      path="/parameters/{parameterID}/",
     *      tags={"item"},
     *      summary="Update item",
     *      @SWG\Parameter(
     *          in="path",
     *          name="parameterID",
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
     *              example="new test name"
     *          ),
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *              @SWG\Schema(
     *                  ref="#definitions/parameter"
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
     */
    public function update(Request $request, $parameterID)
    {
        $this->existOrDie($this::TABLE_NAME, $parameterID);

        $parameter = Parameter::find($parameterID);
        $values = $this->_getRequestValues($request);
        $rules = $this->getUpdateRules();

        $validator = Validator::make($values, $rules);

        if ($validator->fails()) {
            return Error::getStructure(
                $validator->errors()
            );
        }
        $parameter->update($values);

        return response()->json(ParameterStructure::getOne($parameter), 200);
    }

    /**
     * @SWG\Delete(
     *      path="/parameters/{parameterID}",
     *      tags={"parameters"},
     *      summary="Delete parameter",
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
     *              ref="#definitions/error"
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

        return $this->deleteIdentByID($parameterID, '\App\Parameter');
    }
}
