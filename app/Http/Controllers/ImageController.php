<?php

namespace App\Http\Controllers;

use App\Image;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Structures\Error;
use Illuminate\Database\QueryException;
use App\Http\Structures\Parameter as ParameterStructure;
use App\Http\Structures\Image as ImageStructure;
use Validator;


class ImageController extends Controller
{
    private function _getRequestValues($request)
    {
        return [
            'item_id' => $request->name,
            'order' => $request->order,
            'path' => $request->path,
        ];
    }

    protected function getRules()
    {
        return [
            'item_id' => 'required|exists:items,id',
            'order' => 'required|integer',
            'path' => 'required|string',
        ];
    }
    /**
     * @SWG\Get(
     *      path="/parameters",
     *      operationId="getParametersList",
     *      tags={"parameters"},
     *      summary="Get list of parameters",
     *      description="Returns list of parameters",
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="count",
     *                  type="integer",
     *              ),
     *              @SWG\Property(
     *                  property="parameters",
     *                  type="array",
     *                  @SWG\Items(ref="#definitions/parameter")
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(
     *          response=400,
     *          description="Bad request"),
     *     )
     *
     * Returns list of projects
     */
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
        $values = $this->_getRequestValues($request);
        $rules = $this->getRules();
        $validator = Validator::make($values, $rules);

        if ($validator->fails()) {
            return Error::getStructure(
                $validator->errors()
            );
        }

        try {
            $image = Image::create($values);
            return ParameterStructure::getOne($image);
        } catch (QueryException $e) {
            return Error::getStructure('Unexpected error');
        }
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
