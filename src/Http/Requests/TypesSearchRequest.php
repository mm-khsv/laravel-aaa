<?php

namespace dnj\AAA\Http\Requests;

use dnj\AAA\Contracts\IType;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property int|string|null      $id
 * @property string|null          $title
 * @property bool|string|int|null $has_full_access
 */
class TypesSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('viewAny', IType::class);
    }

    public function rules(): array
    {
        return [
            'id' => ['sometimes', 'required', 'numeric'],
            'title' => ['sometimes', 'required', 'string'],
            'has_full_access' => ['sometimes', 'required', 'boolean'],
        ];
    }
}
