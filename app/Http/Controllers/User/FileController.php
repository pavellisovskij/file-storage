<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Models\File;
use App\Models\Folder;
use App\Services\FileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    public function __construct(
        private FileService $fileService
    ) {}

    /**
     * Show the form for creating a new resource.
     *
     * @param Folder $folder
     * @return \Inertia\Response
     */
    public function create(Folder $folder): \Inertia\Response
    {
        return Inertia::render('User/File/Create', [
            'folder' => $folder
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FileRequest $request
     * @param Folder|null $folder
     * @return File|JsonResponse
     */
    public function store(FileRequest $request, Folder $folder = null): File|JsonResponse
    {
        return $this->fileService->store($request->file('file'), $folder);
    }

    /**
     * Display the specified resource.
     *
     * @param File $shared
     * @return \Inertia\Response
     */
    public function show(File $shared): \Inertia\Response
    {
        return Inertia::render('User/File/Show', [
            'file' => $shared->load('user'),
            'downloadLink' => URL::temporarySignedRoute(
                'files.downloadShared',
                now()->addMinutes(5),
                ['shared' => $shared->encoded_name]
            )
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateFileRequest $request
     * @param File $file
     * @return bool
     */
    public function update(UpdateFileRequest $request, File $file): bool
    {
        return $this->fileService->update($file, $request->original_name);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param File $file
     * @return bool
     */
    public function destroy(File $file): bool
    {
        return $this->fileService->delete($file);
    }

    /**
     * Download the specified resource from storage.
     *
     * @param File $file
     * @return StreamedResponse
     */
    public function download(File $file): StreamedResponse
    {
        return Storage::download(
            auth()->id() . '/' . $file->encoded_name,
            $file->original_name . '.' . $file->extension
        );
    }

    /**
     * Create share link
     *
     * @param File $file
     * @return JsonResponse
     */
    public function share(File $file): JsonResponse
    {
        return response()->json([
            'route' => URL::temporarySignedRoute(
                'files.show', now()->addMinutes(5), ['shared' => $file->encoded_name]
            )
        ]);
    }

    /**
     * Download the shared file
     *
     * @param File $shared
     * @return StreamedResponse
     */
    public function downloadShared(File $shared): StreamedResponse
    {
        $shared->load('user');

        return Storage::download(
            $shared->user->id . '/' . $shared->encoded_name,
            $shared->original_name . '.' . $shared->extension
        );
    }
}
