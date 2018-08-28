<?php

namespace App\Http\Controllers;

use App\CategoryParameter;
use App\Http\Structures\Category;
use App\Http\Structures\Error;
use App\Parameter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Item;
use App\Http\Structures\Item as ItemStructure;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Validator;
use App\ItemParameter;

class ItemController extends Controller
{
    private function _getItemParametersRules()
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
            'sku' => 'required|unique:items',
            'price' => 'integer|min:0',
            'image' => 'string'
        ];
    }

    private function _getCategoryParametersRulesByCategoryID($categoryID)
    {
        $parametersID = CategoryParameter::where('category_id', $categoryID)->get(['parameter_id']);
        $parameters = Parameter::whereIn('id', $parametersID)->get(['name']);
        $rules = false;
        foreach ($parameters as $parameter) {
            $rules[$parameter->name] = 'required';
        }
        return $rules;
    }

    private function _prepareItemParameters($values, $itemID){
        $itemParametersNames = array_keys($this->_getItemParametersRules());
        $categoryParametersNames = array_diff(array_keys($values), $itemParametersNames);
        $categoryParameters = Parameter::whereIn('name', $categoryParametersNames)->get(['id', 'name']);
        $data = false;
        foreach ($categoryParameters as $categoryParameter) {
            $value = $values[$categoryParameter->name];
            $data[] = [
                'item_id' => $itemID,
                'parameter_id' => $categoryParameter->id,
                'value' => $value,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        return $data;
    }

    public function index()
    {
        $items = Item::all();
        return ItemStructure::getMany($items);
    }

    /**
     * @SWG\Get(
     *      path="/items/{itemID}/",
     *      operationId="getItem",
     *      tags={"items"},
     *      summary="Get product",
     *      description="Returns product",
     *      @SWG\ItemParameter(
     *           name="itemID",
     *           in="path",
     *           description="Item ID",
     *           required=true,
     *           type="integer",
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *              @SWG\Schema(
     *                  @SWG\Property(
     *                      property="item",
     *                      type="object",
     *                      ref="#definitions/item"
     *                  ),
     *              ),
     *     ),
     *     @SWG\Response(
     *          response=400,
     *          description="Bad request"),
     *     )
     *
     * Returns item
     */
    public function show($itemID)
    {
        $item = Item::with('category', 'parameters')->find($itemID);
        if (is_null($item)) {
            throw new ModelNotFoundException();
        }
        return ItemStructure::getOne($item, true);
    }

    public function store(Request $request)
    {
        $values = $request->all();
        $rules = $this->_getItemParametersRules();
        $categoryParametersRules = $this->_getCategoryParametersRulesByCategoryID($request->category_id);
        if (is_array($categoryParametersRules)) {
            $rules = array_merge($categoryParametersRules, $rules);
        }
        $validator = Validator::make($values, $rules);

        if ($validator->fails()) {
            return Error::getStructure(
                $validator->errors()
            );
        }
        DB::beginTransaction();
        try {
            $item = Item::create($values);
            $itemParameters = $this->_prepareItemParameters($values, $item->id);
            ItemParameter::insert($itemParameters);
            DB::commit();
            return ItemStructure::getOne($item, true);
        } catch (\Exception $e) {
            DB::rollback();
            return Error::getStructure('Unexpected error');
        }
    }

    public function update(Request $request, $itemID)
    {
        $values = $this->_getRequestValues($request);


        dd($requiredNames);
        $validator = $this->_getValidator($values, $requiredNames);

        if ($validator->fails()) {
            return Error::getStructure(
                $validator->errors()
            );
        }
        $item = Item::find($itemID);
        $item->update($values);

        return response()->json(ItemStructure::getOne($item), 200);
    }

    public function delete($itemID)
    {
        return $this->deleteIdentByID($itemID, '\App\Item');
    }
}
