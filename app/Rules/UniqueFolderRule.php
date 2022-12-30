<?php

namespace App\Rules;

use App\Models\Folder;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class UniqueFolderRule implements Rule
{
    private string $originalName;
    private Folder|null $parent;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $originalName, string|null $parentEncodedName = null)
    {
        $this->originalName = $originalName;

        if ($parentEncodedName)
            $this->parent = Folder::query()
                ->where([
                    ['user_id', auth()->id()],
                    ['encoded_name', $parentEncodedName]
                ])
                ->first();
        else $this->parent = null;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $folder = Folder::query()
            ->whereRaw('lower(original_name) = ?', Str::lower($value))
            ->where([
                ['parent_folder_id', $this->parent?->id],
                ['user_id', auth()->id()]
            ])
            ->first();

        return !$folder;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Папка с таким именем уже существует!';
    }
}
