<?php

namespace Modules\Organizations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Organizations\Enums\OrganizationStatus;

class StoreOrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'slug'     => ['nullable', 'string', 'max:255', 'unique:organizations,slug', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'email'    => ['nullable', 'email', 'max:255'],
            'phone'    => ['nullable', 'string', 'max:50'],
            'timezone' => ['nullable', 'string', 'max:100'],
            'locale'   => ['nullable', 'string', 'max:10'],
        ];
    }
}
