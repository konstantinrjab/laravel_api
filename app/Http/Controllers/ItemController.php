<?php

namespace App\Http\Controllers;

use App\Http\Structures\Error;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Item;
use App\Http\Structures\Item as ItemResource;
use Illuminate\Support\Facades\Input;
use App\Category;

class ItemController extends Controller
{
    const TABLE_NAME_ITEMS = 'items';
    const TABLE_NAME_ITEM_PARAMETERS = 'items';

    public function index()
    {
        if (Input::get('parameters')) {
            $items = Item::with('parameters')->get();
        } else {
            $items = Item::all();
        }
        return ItemResource::getItemsStructure($items);
    }

    /**
     * @SWG\Get(
     *      path="/items/{itemID}/",
     *      operationId="getItem",
     *      tags={"items"},
     *      summary="Get product",
     *      description="Returns product",
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
    public function show($id)
    {
        $item = Item::with('category', 'parameters')->find($id);
        return ItemResource::getItemStructure($item, true);
    }

    public function store(Request $request)
    {
        $requestParameters = $request->all();

        $validateErrors = $this->validateParameters(
            $this::TABLE_NAME_ITEMS,
            $requestParameters,
            ['category_id' => 'categories']
        );
        if($validateErrors){
            return Error::getStructure(
                'Parameters are invalid or missing: '.implode(', ', $validateErrors)
            );
        }
        $unqueError = $this->checkInvalidUnique(
            $this::TABLE_NAME_ITEMS,
            ['sku' => $requestParameters['sku']]
        );
        if ($unqueError){
            return Error::getStructure('Unique parameter is busy');
        }

        try{
            $item = Item::create($request->all());
            return ItemResource::getItemStructure($item);
        } catch (QueryException $e){
//            return Error::getStructure('Unexpected error');
            return Error::getStructure($e);
        }
    }

    public function update(Request $request, Item $item)
    {
        $item->update($request->all());

        return response()->json($item, 200);
    }

    public function delete(Item $item)
    {
        $item->delete();

        return response()->json(null, 204);
    }
}
