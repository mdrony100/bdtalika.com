<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class GeneralSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'logo'              => 'mimes:jpeg,jpg,png,svg',
            'og_image'          => 'mimes:jpeg,jpg,png,svg',
            'favicon'           => 'mimes:jpeg,jpg,png,svg',
            'loader'            => 'mimes:gif',
            'admin_loader'      => 'mimes:gif',
            'affilate_banner'   => 'mimes:jpeg,jpg,png,svg',
            'error_banner'      => 'mimes:jpeg,jpg,png,svg',
            'popup_background'  => 'mimes:jpeg,jpg,png,svg',
            'invoice_logo'      => 'mimes:jpeg,jpg,png,svg',
            'breadcumb_banner'  => 'mimes:jpeg,jpg,png,svg',
            'footer_logo'       => 'mimes:jpeg,jpg,png,svg',
            'cert_sign'         => 'mimes:jpeg,jpg,png,svg',
            'footer'            => 'min:10',
            'copyright'         => 'min:10',
            
        ];
    }



    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        throw new ValidationException($validator, $response);
    }
}
