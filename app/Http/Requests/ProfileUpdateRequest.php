<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'string', 'max:255', Rule::unique('users')->ignore(Auth::user())],
            'password'  => ['nullable', 'string', 'confirmed', 'min:8'],
            'image'     => ['required','mimes:jpg,jpeg,png,bmp,tiff','max:4096'],
        ];
    }

    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->password == null) {
            $this->request->remove('password');
        }

        if ($this->image == null) {
            $this->request->remove('image');
        }
    }
}
