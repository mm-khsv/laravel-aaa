<?php

namespace dnj\AAA\Http\Requests;

use dnj\AAA\Contracts\IUserManager;
use dnj\AAA\Contracts\UserStatus;
use dnj\AAA\Rules\TypeExists;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string|null     $name
 * @property int|null        $type_id
 * @property UserStatus|null $status
 */
class UserUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = app(IUserManager::class)->findOrFail($this->route('user'));

        return $this->user()->can('update', $user);
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string'],
            'type_id' => ['sometimes', 'required', app(TypeExists::class)->userHasAccess($this->user())],
            'status' => ['sometimes', 'required', Rule::enum(UserStatus::class)],
        ];
    }
}
