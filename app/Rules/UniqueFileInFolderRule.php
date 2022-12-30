<?php

namespace App\Rules;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

class UniqueFileInFolderRule implements Rule
{
    private string $originalName;
    private Folder|null $folder;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $originalName, string|null $folderEncodedName = null)
    {
        $this->originalName = $originalName;

        if ($folderEncodedName)
            $this->folder = Folder::query()
                ->where([
                    ['user_id', auth()->id()],
                    ['encoded_name', $folderEncodedName]
                ])
                ->first();
        else $this->folder = null;
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
        $folder = File::query()
            ->whereRaw('lower(original_name) = ?', Str::lower($value))
            ->where([
                ['folder_id', $this->folder?->id],
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
        return 'Файл с таким именем уже существует в папке!';
    }
}
