<?php

namespace App\Http\Controllers;

use App\Image;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Structures\Error;
use Illuminate\Database\QueryException;
use App\Http\Structures\Parameter as ParameterStructure;
use App\Http\Structures\Image as ImageStructure;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Validator;
use Illuminate\Support\Facades\DB;


class ImageController extends Controller
{
    private function _getRequestValues($request)
    {
        return [
            'item_id' => $request->name,
            'order' => $request->order,
            'image' => $request->image,
        ];
    }

    protected function getRules()
    {
        return [
            'item_id' => 'required|exists:items,id',
            'order' => 'required|integer',
            'image' => 'required|image|mimes:jpeg|max:2048'
//            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }

//    public function index()
//    {
//        $parameters = Image::all();
//        return ImageStructure::getMany($parameters);
//    }

    public function show($imageID)
    {
        $image = Image::find($imageID);
        if (is_null($image)) {
            throw new ModelNotFoundException();
        }
        return ImageStructure::getOne($image);
    }

    public function store(Request $request)
    {
        $rules = $this->getRules();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Error::getStructure(
                $validator->errors()
            );
        }

        $file = $request->file('image');
        if ($file) {
            DB::beginTransaction();
            try {
                $filename = 'item-' . $request->item_id . '-' . uniqid() . '.jpg';
                Storage::disk('public')->put($filename, File::get($file));
                $image = Image::create([
                    'item_id' => $request->item_id,
                    'order' => $request->order,
                    'path' => $filename,
                ]);
                DB::commit();
                return ImageStructure::getOne($image);
            } catch (QueryException $e) {
                DB::rollback();
                return $e;
            }
        }
//        $values = $this->_getRequestValues($request);
//        $rules = $this->getRules();
//        $validator = Validator::make($values, $rules);
//
//        if ($validator->fails()) {
//            return Error::getStructure(
//                $validator->errors()
//            );
//        }
//
//        try {
//            $image = Image::create($values);
//            return ParameterStructure::getOne($image);
//        } catch (QueryException $e) {
//            return Error::getStructure('Unexpected error');
//        }
    }

    public function update(Request $request, $imageID)
    {
        $image = Image::find($imageID);
        if (is_null($image)) {
            throw new ModelNotFoundException();
        }

        $values = $this->_getRequestValues($request);
        $rules = $this->getUpdateRules();

        $validator = Validator::make($values, $rules);

        if ($validator->fails()) {
            return Error::getStructure(
                $validator->errors()
            );
        }
        $image->update($values);

        return response()->json(ParameterStructure::getOne($image), 200);
    }

    public function delete($imageID)
    {
        return $this->deleteIdentByID($imageID, '\App\Image');
    }
}
