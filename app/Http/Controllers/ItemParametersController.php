<?php

namespace App\Http\Controllers;

use App\ItemParameter;
use Illuminate\Http\Request;
use App\Http\Structures\Error;
use Illuminate\Database\QueryException;
use App\Http\Structures\ItemParameter as ItemParameterStructure;
use Validator;


class ItemParametersController extends Controller
{
    const TABLE_NAME = 'item_parameter';

    private function _getRequestValues($request)
    {
        return [
            'item_id' => $request->item_id,
            'parameter_id' => $request->parameter_id,
            'value' => $request->value,
        ];
    }

    protected function getRules()
    {
        return [
            'item_id' => 'required|exists:items,id',
            'parameter_id' => 'required|exists:parameters,id',
            'value' => 'required',
        ];
    }

    /**
     * @SWG\Get(
     *      path="/items/parameters/",
     *      tags={"itemParameter"},
     *      summary="Get list of itemParameters",
     *      description="Returns list of itemParameters",
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="parameters",
     *                  type="array",
     *                  @SWG\Items(ref="#definitions/itemParameter")
     *              ),
     *          )
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
        $parameters = ItemParameter::all();
        return ItemParameterStructure::getMany($parameters);
    }

    /**
     * @SWG\Post(
     *      path="/items/parameters/",
     *      tags={"itemParameter"},
     *      summary="Add itemParameter",
     *      @SWG\Parameter(
     *          in="formData",
     *          name="item_id",
     *          type="integer",
     *          required=true,
     *          @SWG\Schema(
     *              example="1"
     *          ),
     *      ),
     *      @SWG\Parameter(
     *          in="formData",
     *          name="parameter_id",
     *          type="string",
     *          required=true,
     *          @SWG\Schema(
     *              example="1"
     *          ),
     *      ),
     *      @SWG\Parameter(
     *          in="formData",
     *          name="value",
     *          type="string",
     *          required=true,
     *          @SWG\Schema(
     *              example="val 1"
     *          ),
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *              @SWG\Schema(
     *                  ref="#definitions/itemParameter"
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
            $parameter = ItemParameter::create($values);
            return ItemParameterStructure::getOne($parameter);
        } catch (QueryException $e) {
            return $e;
        }
    }


    /**
     * @SWG\Post(
     *      path="/items/parameters/{parameterID}/",
     *      tags={"itemParameter"},
     *      summary="Update itemParameter",
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
     *          name="item_id",
     *          type="integer",
     *          @SWG\Schema(
     *              example="1"
     *          ),
     *      ),
     *      @SWG\Parameter(
     *          in="formData",
     *          name="parameter_id",
     *          type="string",
     *          @SWG\Schema(
     *              example="test name 1"
     *          ),
     *      ),
     *      @SWG\Parameter(
     *          in="formData",
     *          name="value",
     *          type="string",
     *          @SWG\Schema(
     *              example="1"
     *          ),
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *              @SWG\Schema(
     *                  ref="#definitions/itemParameter"
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
        $parameter = ItemParameter::find($parameterID);

        $values = $this->_getRequestValues($request);
        $rules = $this->getUpdateRules();

        $validator = Validator::make($values, $rules);

        if ($validator->fails()) {
            return Error::getStructure(
                $validator->errors()
            );
        }
        $parameter->update($values);

        return response()->json(ItemParameterStructure::getOne($parameter), 200);
    }

    /**
     * @SWG\Delete(
     *      path="/items/parameters/{parameterID}/",
     *      tags={"itemParameter"},
     *      summary="Delete itemParameter",
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
     */
    public function delete($parameterID)
    {
        $this->existOrDie($this::TABLE_NAME, $parameterID);

        return $this->deleteIdentByID($parameterID, '\App\ItemParameter');
    }
}
