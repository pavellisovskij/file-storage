<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateFolderRequest;
use App\Models\Folder;
use App\Services\FolderService;
use Illuminate\Http\JsonResponse;

class FolderController extends Controller
{
    public function __construct(
        private FolderService $folderService
    ) {}

    /**
     * Store a newly created resource in storage.
     *
     * @param UpdateFolderRequest $request
     * @param Folder|null $parent
     * @return JsonResponse
     */
    public function store(UpdateFolderRequest $request, Folder $parent = null): JsonResponse
    {
        return response()->json($this->folderService->store($parent, $request->validated()));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateFolderRequest $request
     * @param Folder $folder
     * @return bool
     */
    public function update(UpdateFolderRequest $request, Folder $folder): bool
    {
        return $this->folderService->update($folder, $request->original_name);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Folder $folder
     * @return bool
     */
    public function destroy(Folder $folder): bool
    {
        return $this->folderService->delete($folder);
    }
}
