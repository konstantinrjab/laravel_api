<?php

namespace App\Http\Requests324213;
use App\Http\Requests324213\APIRequest;
use Illuminate\Contracts\Validation\Validator;
use App\CategoryParameter;
use App\Http\Structures\CategoryParameter as CategoryParameterStructure;
use App\Http\Structures\Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddCategoryParameterRequest extends APIRequest
{
    protected function responseCode()
    {
        return 417;
    }

    public function rules()
    {
        return [
            'category_id' => 'required',
            'parameter_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'OOO'
        ];
    }

    public function store(Request $request)
    {
        return 222;
        $values = Input::all();
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'parameter_id' => 'required',
        ]);
        return 123;
        if ($validator->fails()) {
            return Error::getStructure(
                'Parameters are invalid or missing: '.$validator
            );
//            return redirect('post/create')
//                ->withErrors($validator)
//                ->withInput();
        }
//        $validateErrors = $this->validateParameters(
//            $this::TABLE_NAME,
//            $values,
//            ['category_id' => 'categories']
//        );
//
//        $parameter = CategoryParameter::create($values);
//        return CategoryParameterStructure::getParameterStructure($parameter);

        return response()->json('ololo', 201);
    }
}