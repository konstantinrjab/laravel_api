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
    const TABLE_NAME = 'item_parameters';

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
     *      path="items/parameters",
     *      tags={"parameter"},
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

    public function delete($parameterID)
    {
        $this->existOrDie($this::TABLE_NAME, $parameterID);

        return $this->deleteIdentByID($parameterID, '\App\ItemParameter');
    }
}
