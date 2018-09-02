<?php

namespace App\Http\Controllers;

use App\CategoryParameter;
use App\Http\Structures\Error;
use App\Parameter;
use Illuminate\Http\Request;
use App\Item;
use App\Http\Structures\Item as ItemStructure;
use Illuminate\Support\Facades\DB;
use Validator;
use App\ItemParameter;

class ItemController extends Controller
{
    const TABLE_NAME = 'items';

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
        $data = false;
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

    /**
     * @SWG\Get(
     *      path="/items",
     *      tags={"item"},
     *      summary="Get list of items",
     *      description="Returns list of items",
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="items",
     *                  type="array",
     *                  @SWG\Items(ref="#definitions/item")
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
        $items = Item::all();
        return ItemStructure::getMany($items);
    }

    /**
     * @SWG\Get(
     *      path="/items/{itemID}/",
     *      tags={"item"},
     *      summary="Get item",
     *      description="Returns item",
     *      @SWG\Parameter(
     *           name="itemID",
     *           in="path",
     *           description="Item ID",
     *           required=true,
     *           type="integer",
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="item",
     *                  type="object",
     *                  ref="#definitions/item"
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(
     *          response="default",
     *          description="Error",
     *     )
     *  )
     *
     * Returns item
     */
    public function show($itemID)
    {
        $this->existOrDie($this::TABLE_NAME, $itemID);

        $item = Item::with('category')->find($itemID);
        return ItemStructure::getOne($item, true);
    }

    /**
     * @SWG\Post(
     *      path="/items",
     *      tags={"categoryParameter"},
     *      summary="Add categoryParameter",
     *      @SWG\Parameter(
     *          in="formData",
     *          name="category_id",
     *          type="integer",
     *          required=true,
     *          @SWG\Schema(
     *              example="1"
     *          ),
     *      ),
     *      @SWG\Parameter(
     *          in="formData",
     *          name="name",
     *          type="string",
     *          required=true,
     *          @SWG\Schema(
     *              example="test name 1"
     *          ),
     *      ),
     *      @SWG\Parameter(
     *          in="formData",
     *          name="sku",
     *          type="string",
     *          required=true,
     *          @SWG\Schema(
     *              example="1"
     *          ),
     *      ),
     *      @SWG\Parameter(
     *          in="formData",
     *          name="price",
     *          type="string",
     *          required=true,
     *          @SWG\Schema(
     *              example="200"
     *          ),
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
     *          ),
     *     @SWG\Response(
     *          response="default",
     *          description="Error",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="error",
     *                  type="object",
     *                  ref="#definitions/error"
     *              ),
     *          )
     *     ),
     *     security={{"api_key":{}}}
     *  )
     *
     * @param $request
     * @return mixed
     * Add item
     */
    public function store(Request $request)
    {
        $rules = $this->_getValidationRules($request);
        $validator = Validator::make(
            $request->all(),
            $rules,
            ['required' => 'Missing item or category parameter: :attribute']
        );

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
            if($additionalParameters){
                ItemParameter::insert($additionalParameters);
            }

            DB::commit();
            return ItemStructure::getOne($item, true);
        } catch (\Exception $e) {
            DB::rollback();
            return Error::getStructure('invalid input / parameters does not exist');
        }
    }

    /**
     * @SWG\Post(
     *      path="/items/{itemID}",
     *      tags={"item"},
     *      summary="Update item",
     *      @SWG\Parameter(
     *          in="path",
     *          name="itemID",
     *          required=true,
     *          type="integer",
     *          @SWG\Schema(
     *              example="1"
     *          ),
     *      ),
     *      @SWG\Parameter(
     *          in="formData",
     *          name="category_id",
     *          type="integer",
     *          @SWG\Schema(
     *              example="1"
     *          ),
     *      ),
     *      @SWG\Parameter(
     *          in="formData",
     *          name="name",
     *          type="string",
     *          @SWG\Schema(
     *              example="test name 1"
     *          ),
     *      ),
     *      @SWG\Parameter(
     *          in="formData",
     *          name="sku",
     *          type="string",
     *          @SWG\Schema(
     *              example="1"
     *          ),
     *      ),
     *      @SWG\Parameter(
     *          in="formData",
     *          name="price",
     *          type="string",
     *          @SWG\Schema(
     *              example="200"
     *          ),
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
     *          ),
     *     @SWG\Response(
     *          response="default",
     *          description="Error",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="error",
     *                  type="object",
     *                  ref="#definitions/error"
     *              ),
     *          )
     *     ),
     *     security={{"api_key":{}}}
     *  )
     *
     * Update item
     */
    public function update(Request $request, $itemID)
    {
        $this->existOrDie($this::TABLE_NAME, $itemID);
        $item = Item::find($itemID);

        $rules = $this->getUpdateRules();
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Error::getStructure(
                $validator->errors()
            );
        }

        $item->update($request->all());

        return response()->json(ItemStructure::getOne($item, true), 200);
    }

    /**
     * @SWG\Delete(
     *      path="/items/{itemID}",
     *      tags={"item"},
     *      summary="Delete item",
     *      @SWG\Parameter(
     *          in="path",
     *          name="itemID",
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
     *              @SWG\Property(
     *                  property="error",
     *                  type="object",
     *                  ref="#definitions/error"
     *              ),
     *          )
     *     ),
     *     security={{"api_key":{}}}
     *  )
     *
     * Delete item
     */
    public function delete($itemID)
    {
        $this->existOrDie($this::TABLE_NAME, $itemID);

        return $this->deleteIdentByID($itemID, '\App\Item');
    }
}
