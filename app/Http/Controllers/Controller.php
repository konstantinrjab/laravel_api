<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;


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

    protected function validateParameters($tableName, $requestParameters){

        return $this->_getMissingParameters($tableName, $requestParameters);
    }

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

        return (!empty($missingKeys) ? $missingKeys : false);
    }
}
