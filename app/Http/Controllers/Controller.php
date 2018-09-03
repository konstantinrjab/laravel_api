<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Structures\Error;
use Illuminate\Database\QueryException;
use Validator;

/**
 * @SWG\Swagger(
 *     basePath="/",
 *     schemes={"http"},
 *     host=L5_SWAGGER_CONST_HOST,
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="L5 Swagger API",
 *         description="L5 Swagger API description",
 *         @SWG\Contact(
 *             email="konstantinrjab@gmail.com"
 *         ),
 *     )
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function deleteIdentByID($id, string $className)
    {
        $ident = $className::find($id);
        return $this->deleteIdent($ident);
    }

    protected function deleteIdent($ident)
    {
        if (is_null($ident)) {
            return response()->json(Error::getStructure('resource not found'), 404);
        }
        try {
            $ident->delete();
            return response()->json(null, 204);
        } catch (QueryException $e) {
            return Error::getStructure('Unexpected error');
        }
    }

    protected function getUpdateRules()
    {
        $rules = $this->getRules();
        foreach ($rules as &$rule) {
            $rule = 'sometimes|' . $rule;
        }
        return $rules;
    }

    protected function existOrDie($tablelName, $id)
    {
        $validator = Validator::make(['id' => $id], ['id' => "exists:$tablelName,id"]);
        if ($validator->fails()) {
            throw new ModelNotFoundException();
        }
    }
}
