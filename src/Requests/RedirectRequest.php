<?php

namespace ModularShopify\ModularShopify\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class RedirectRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->hasValidSignature();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'domain' => 'required',
            'id' => 'required',
            'signature' => 'required',
            'transactionid' => 'required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        Log::error('Failed to redirect user to success page', [
            'event' => 'user_redirect_failure',
            'error' => $validator->errors(),
        ]);
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

    protected function prepareForValidation()
    {
        $customQueryString = http_build_query($this->only(['domain', 'id', 'signature']));
        $this->server->set('QUERY_STRING', $customQueryString);

    }
}
