<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Http\Resources\Item as ItemResource;

class ItemWebController extends Controller
{
    public function index(Request $request)
    {
//        if($request->input('category')){
//            $response = Item::all();
//        }
        
        return view('items', ['items' => $request]);
    }
    
    public function show($id)
    {
        $item = Item::with(['category'])->find($id);
        return view('item', ['item' => $item]);
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
