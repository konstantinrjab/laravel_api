<?php

namespace App\Http\Controllers;

use DemeterChain\C;
use Illuminate\Http\Request;
use App\Item;
use App\Http\Structures\Item as ItemResource;
use Illuminate\Support\Facades\Input;

class ItemController extends Controller
{
    public function index()
    {
        if(Input::get('parameters')){
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
        return ItemResource::getItemStructure($item);
    }
    
    public function store(Request $request)
    {
        $item = Item::create($request->all());
        
        return response()->json($item, 201);
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
