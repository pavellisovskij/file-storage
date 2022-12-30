<?php

namespace App\Services;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Support\Facades\Storage;

class FolderService
{
    /**
     * Stores a new folder record to the database
     *
     * @param Folder|null $parentFolder
     * @param array $data
     * @return Folder
     */
    public function store(Folder|null $parentFolder, array $data): Folder
    {
        return Folder::create(array_merge($data, [
            'user_id' => auth()->id(),
            'encoded_name' => md5($data['original_name'] . time()),
            'parent_folder_id' => $parentFolder?->id,
        ]));
    }

    /**
     * Updates the original name of the folder
     *
     * @param Folder $folder
     * @param string $name
     * @return bool
     */
    public function update(Folder $folder, string $name): bool
    {
        return $folder->update(['original_name' => $name]);
    }


    /**
     * Deletes the folder with children folders and files
     *
     * @param Folder $folder
     * @return bool|null
     */
    public function delete(Folder $folder): ?bool
    {
        $folder->load('files');

        if (count($folder->files) > 0) {
            Storage::delete($folder->files->map(function (File $file) {
                return ((string)auth()->id()) . '/' . $file->encoded_name;
            }));

            Storage::delete($folder->files->map(function (File $file) {
                return ((string)auth()->id()) . '/thumbnail/' . $file->encoded_name . '.' . $file->extension;
            }));
        }

        return $folder->delete();
    }
}
