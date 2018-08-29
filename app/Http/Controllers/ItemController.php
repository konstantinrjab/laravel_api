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
    private $categoryParameters;
    private $itemParameters;

    public function __construct()
    {
        $this->itemParameters = array_keys($this->getRules());
    }

    protected function getRules()
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
        $this->categoryParameters = (is_array($rules) ? array_keys($rules) : []);
        return $rules;
    }

    private function _prepareCategoryParametersByItemID($values, $itemID)
    {
        $parameters = array_diff(array_keys($values), $this->itemParameters);
        $categoryParameters = Parameter::whereIn('name', $parameters)->get(['id', 'name']);
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

    private function _getValidationRules($request)
    {
        $rules = $this->getRules();
        $categoryRules = $this->_getCategoryParametersRulesByCategoryID($request->category_id);
        if (is_array($categoryRules)) {
            $rules = array_merge($categoryRules, $rules);
        }

        $itemParameters = array_diff_key(
            $request->all(),
            $rules
        );
        $itemParametersRules = [];
        foreach ($itemParameters as $name => $value) {
            $itemParametersRules = array_merge(
                $itemParametersRules,
                [
                    $name => 'required'
                ]);
        }
        if (is_array($itemParametersRules)) {
            $rules = array_merge($itemParametersRules, $rules);
        }

        return $rules;
    }

    private function _prepareAdditionalParameters($request, $itemID)
    {
        $additionalParameters = array_diff_key(
            $request,
            array_flip($this->itemParameters),
            array_flip($this->categoryParameters)
        );
        $additionalParameters = Parameter::whereIn('name', array_keys($additionalParameters))->get(['id', 'name']);
        foreach ($additionalParameters as $parameter) {
            $data[] = [
                'item_id' => $itemID,
                'parameter_id' => $parameter->id,
                'value' => $request[$parameter->name],
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
        $item = Item::with('category')->find($itemID);
        if (is_null($item)) {
            throw new ModelNotFoundException();
        }
        return ItemStructure::getOne($item, true);
    }

    public function store(Request $request)
    {
        $rules = $this->_getValidationRules($request);
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Error::getStructure(
                $validator->errors()
            );
        }

        DB::beginTransaction();
        try {
            $item = Item::create($request->all());
            $categoryParameters = $this->_prepareCategoryParametersByItemID($request->all(), $item->id);
            ItemParameter::insert($categoryParameters);

            $additionalParameters = $this->_prepareAdditionalParameters($request->all(), $item->id);
            ItemParameter::insert($additionalParameters);

            DB::commit();
            return ItemStructure::getOne($item, true);
        } catch (\Exception $e) {
            DB::rollback();
            return Error::getStructure('invalid input / parameters does not exist');
        }
    }

    public function update(Request $request, $itemID)
    {
        $rules = $this->getUpdateRules();
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Error::getStructure(
                $validator->errors()
            );
        }
        $item = Item::find($itemID);
        $item->update($request->all());

        return response()->json(ItemStructure::getOne($item, true), 200);
    }

    public function delete($itemID)
    {
        return $this->deleteIdentByID($itemID, '\App\Item');
    }
}
