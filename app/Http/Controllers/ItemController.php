<?php

namespace App\Http\Controllers;

use App\Http\Structures\Error;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Item;
use App\Http\Structures\Item as ItemStructure;
use Validator;

class ItemController extends Controller
{
    private function _getRequestValues($request)
    {
        return [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'sku' => $request->sku,
            'image' => $request->image,
            'price' => $request->price,
        ];
    }

    private function _getValidator($values)
    {
        return Validator::make($values, [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required',
            'sku' => 'required|unique:items',
            'price' => 'integer|min:0'
        ]);
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
        if(is_null($item)){
            throw new ModelNotFoundException();
        }
        return ItemStructure::getOne($item, true);
    }

    public function store(Request $request)
    {
        $values = $this->_getRequestValues($request);
        $validator = $this->_getValidator($values);

        if ($validator->fails()) {
            return Error::getStructure(
                $validator->errors()
            );
        }

        try {
            $item = Item::create($values);
            return ItemStructure::getOne($item);
        } catch (QueryException $e) {
            return Error::getStructure('Unexpected error');
        }
    }

    public function update(Request $request, $itemID)
    {
        $values = $this->_getRequestValues($request);
        $validator = $this->_getValidator($values);

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
