<?php

namespace dnj\AAA\Http\Requests;

use dnj\AAA\Contracts\ITypeManager;
use dnj\AAA\Contracts\IUserManager;
use dnj\AAA\Contracts\UserStatus;
use dnj\AAA\Rules\AbilityRule;
use dnj\AAA\Rules\TypeExists;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property array<array{title?:string}>|null               $translates
 * @property string[]|null                  $abilities
 * @property array<string|int>|null           $children
 */
class TypeUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $type = app(ITypeManager::class)->findOrFail($this->route('type'));

        return $this->user()->can('update', $type);
    }

    public function rules(): array
    {
        return [
            'translates.*.title' => ['sometimes', 'required', 'string'],
            'abilities' => ['sometimes', 'required', 'array'],
            'abilities.*' => ['required', new AbilityRule()],
            'children' => ['sometimes', 'required', 'array'],
            'children.*' => ['required', app(TypeExists::class)->userHasAccess($this->user())],
        ];
    }
}
