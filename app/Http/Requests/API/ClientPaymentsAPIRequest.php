<?php


namespace App\Http\Requests\API;

use InfyOm\Generator\Request\APIRequest;

class ClientPaymentsAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize ()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules ()
    {
        return [
            'client' => 'nullable|integer|exists:clients,id',
            'skip'   => 'nullable|integer',
            'limit'  => 'nullable|integer',
        ];
    }
}
