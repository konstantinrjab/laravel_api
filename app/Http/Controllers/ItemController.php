<?php

namespace App\Http\Controllers;

use App\Category;
use DemeterChain\C;
use Illuminate\Http\Request;
use App\Item;
use App\Http\Resources\Item as ItemResource;

class ItemController extends Controller
{
    public function index()
    {
        return Item::all();
    }

    public function show($id)
    {
        return ItemResource::getStructure(Item::find($id));
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
