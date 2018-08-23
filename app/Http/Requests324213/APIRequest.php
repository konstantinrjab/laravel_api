<?php

namespace App\Http\Requests324213;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class APIRequest extends FormRequest
{
    public function response(array $errors)
    {
         return new JsonResponse($errors, $this->responseCode());
    }

    protected function responseCode()
    {
        return 422;
    }
}