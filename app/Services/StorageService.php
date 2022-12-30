<?php

namespace App\Services;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Database\Eloquent\Collection;

class StorageService
{
    /**
     * Returns a collection of folders for the root of the user's storage
     *
     * @return Collection|array
     */
    public function getRootFolders(): Collection|array
    {
        return Folder::query()
            ->where([
                ['parent_folder_id', null],
                ['user_id', auth()->id()]
            ])
            ->orderBy('original_name')
            ->get();
    }

    /**
     * Returns a collection of files for the root of the user's storage
     *
     * @return Collection|array
     */
    public function getRootFiles(): Collection|array
    {
        return File::query()
            ->where([
                ['folder_id', null],
                ['user_id', auth()->id()]
            ])
            ->orderBy('original_name')
            ->get();
    }

    /**
     * Searches folders by the original name
     *
     * @param string $search
     * @return Collection|array
     */
    public function searchFolders(string $search): Collection|array
    {
        return Folder::query()
            ->where([
                ['user_id', auth()->id()],
                ['original_name', 'LIKE', '%' . $search . '%']
            ])
            ->orderBy('original_name')
            ->get();
    }

    /**
     * Searches files by the original name
     *
     * @param string $search
     * @return Collection|array
     */
    public function searchFiles(string $search): Collection|array
    {
        return File::query()
            ->where([
                ['user_id', auth()->id()],
                ['original_name', 'LIKE', '%' . $search . '%']
            ])
            ->orderBy('original_name')
            ->get();
    }
}
