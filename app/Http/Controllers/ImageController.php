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
    const TABLE_NAME = 'images';

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
            'item_id' => 'required|integer|exists:items,id',
            'order' => 'required|integer|min:0|max:127',
            'image' => 'required|image|mimes:jpeg|max:2048'
//            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }

//    public function index()
//    {
//        $parameters = Image::all();
//        return ImageStructure::getMany($parameters);
//    }

    /**
     * @SWG\Get(
     *      path="/images/{imageID}/",
     *      tags={"image"},
     *      summary="Get image",
     *      description="Returns image",
     *      @SWG\Parameter(
     *           name="imageID",
     *           in="path",
     *           description="Image ID",
     *           required=true,
     *           type="integer",
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              ref="#definitions/image"
     *          ),
     *     ),
     *     @SWG\Response(
     *          response="default",
     *          description="Error",
     *     )
     *  )
     */
    public function show($imageID)
    {
        $image = Image::find($imageID);
        if (is_null($image)) {
            throw new ModelNotFoundException();
        }
        return ImageStructure::getOne($image);
    }

    /**
     * @SWG\Post(
     *      path="/images/",
     *      tags={"image"},
     *      summary="Upload image",
     *      @SWG\Parameter(
     *          in="formData",
     *          name="image",
     *          type="file",
     *          required=true,
     *      ),
     *      @SWG\Parameter(
     *          in="formData",
     *          name="item_id",
     *          type="integer",
     *          required=true,
     *          @SWG\Schema(
     *              example="1"
     *          ),
     *      ),
     *      @SWG\Parameter(
     *          in="formData",
     *          name="order",
     *          type="integer",
     *          required=true,
     *          minimum=0,
     *          maximum=127,
     *          @SWG\Schema(
     *              example="1"
     *          ),
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              ref="#definitions/image"
     *          ),
     *     ),
     *     @SWG\Response(
     *          response="default",
     *          description="Error",
     *          @SWG\Schema(
     *              ref="#definitions/error"
     *          )
     *     ),
     *     security={{"api_key":{}}}
     *  )
     */
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

    //ADD delete image instance
    public function delete($imageID)
    {
        $this->existOrDie($this::TABLE_NAME, $imageID);

        return $this->deleteIdentByID($imageID, '\App\Image');
    }
}
