<?php

namespace App\Http\Requests;

use App\Helpers\Constants\RolesEnum;
use App\Helpers\Constants\StatusEnum;
use App\Helpers\Constants\StorageSizesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

/**
 * @property int $storage_size
 * @property int $role
 * @property int $status
 */
class UpdateUserRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'storage_size' => 'required|integer|between:1,' . round(StorageSizesEnum::MAX->value / 1024 / 1024),
            'role' => [
                'required',
                new Enum(RolesEnum::class)
            ],
            'status' => [
                'required',
                new Enum(StatusEnum::class)
            ],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'storage_size' => (
                $this->role === RolesEnum::ADMIN->value ?
                    (StorageSizesEnum::MAX->value / 1024 / 1024) :
                    $this->storage_size
                ),
        ]);
    }
}
