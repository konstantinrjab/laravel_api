<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use App\Http\Structures\Error;
use App\Category;
use Illuminate\Contracts\Validation\Validator;


/**
 * @SWG\Swagger(
 *     basePath="/",
 *     schemes={"http", "https"},
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

    protected function validateParameters($tableName, $requestParameters, $relations = array())
    {
        $missingParameters = $this->_getMissingParameters($tableName, $requestParameters);
        if ($missingParameters) {
            return $missingParameters;
        }
        if (!empty($relations)) {
            $invalidRelations = $this->_getInvalidRelations($requestParameters, $relations);
            return $invalidRelations;
        }

        return false;
    }

    /**
     * @param $tableName
     * @param $requestParameters
     * @return array|bool
     */
    private function _getMissingParameters($tableName, $requestParameters)
    {
        $requestParameters = array_filter($requestParameters);
        $requestKeys = array_keys($requestParameters);
        $requiredKeys = DB::table('information_schema.COLUMNS')
            ->where('TABLE_SCHEMA', '=', DB::connection()->getDatabaseName())
            ->where('TABLE_NAME', '=', $tableName)
            ->where('IS_NULLABLE', '=', 'NO')
            ->where('COLUMN_NAME', '<>', 'id')
            ->pluck('COLUMN_NAME')
            ->toArray();
        $missingKeys = array_diff($requiredKeys, $requestKeys);

        if ($missingKeys) {
            $this->trowError('Parameters are invalid or missing: ' . implode(', ', $missingKeys));
        }
        return (!empty($missingKeys) ? $missingKeys : false);
    }

    /**
     * @param $requestParameters
     * @param $relations
     * @return array|bool
     */
    private function _getInvalidRelations($requestParameters, $relations)
    {
        foreach ($relations as $column => $tableName) {
            $exist = DB::table($tableName)
                ->where('id', '=', $requestParameters[$column])->count();
            if (!$exist) {
                return $column;
            }
        }
        return false;
    }

    protected function checkBusyUnique($tableName, $fields)
    {
        $query = DB::table($tableName);
        foreach ($fields as $key => $value) {
            $query->where($key, '=', $value);
        }
        $result = $query->count();
        if ($result) {
            return 'unique value is busy';
        }
        return false;
    }

    public function trowError($message)
    {
        $response = Error::getStructure($message);
        return response()->json($response);
    }
}
