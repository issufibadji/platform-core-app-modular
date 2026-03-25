<?php

namespace Modules\Organizations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Organizations\Enums\OrganizationStatus;

class UpdateOrganizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $organizationId = $this->route('organization');

        return [
            'name'     => ['required', 'string', 'max:255'],
            'slug'     => ['nullable', 'string', 'max:255', Rule::unique('organizations', 'slug')->ignore($organizationId), 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'status'   => ['required', Rule::in(OrganizationStatus::values())],
            'email'    => ['nullable', 'email', 'max:255'],
            'phone'    => ['nullable', 'string', 'max:50'],
            'timezone' => ['nullable', 'string', 'max:100'],
            'locale'   => ['nullable', 'string', 'max:10'],
        ];
    }
}
