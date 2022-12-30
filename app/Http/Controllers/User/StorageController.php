<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Models\Folder;
use App\Services\StorageService;
use Inertia\Inertia;
use Inertia\Response;

class StorageController extends Controller
{
    public function __construct(
        private StorageService $storageService
    ) {}

    /**
     * Show list of user's files and folders
     *
     * @return Response
     */
    public function root(): Response
    {
        return Inertia::render('User/Storage/Root', [
            'files' => $this->storageService->getRootFiles(),
            'folders' => $this->storageService->getRootFolders()
        ]);
    }

    /**
     * Show list of user's files and children folders in the folder
     *
     * @param Folder $folder
     * @return Response
     */
    public function folder(Folder $folder): Response
    {
        return Inertia::render('User/Storage/Folder', [
            'curFolder' => $folder->load([
                'children_folders' => function($query) {
                    $query->orderBy('original_name');
                },
                'files' => function($query) {
                    $query->orderBy('original_name');
                },
                'parent_folder'
            ])
        ]);
    }

    /**
     * Search files and folders in the user's storage
     *
     * @param SearchRequest $request
     * @return Response
     */
    public function search(SearchRequest $request): Response
    {
        if (!$request->get('search')) {
            abort(404);
        }

        return Inertia::render('User/Storage/Search', [
            'searchable' => $request->search,
            'folders' => $this->storageService->searchFolders($request->search),
            'files' => $this->storageService->searchFiles($request->search)
        ]);
    }
}
