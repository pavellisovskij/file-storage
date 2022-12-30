<?php

namespace App\Services;

use App\Helpers\Constants\RolesEnum;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class FileService
{
    /**
     * Stores the uploaded file to the database and the file storage. If the uploaded file is an image, creates the
     * thumbnail in the file storage
     *
     * @param UploadedFile $file
     * @param Folder|null $folder
     * @return File|JsonResponse
     */
    public function store(UploadedFile $file, Folder|null $folder): File|JsonResponse
    {
        $user = auth()->user();
        $filename = md5($file->getClientOriginalName() . time());

        // if user role is admin or the storage size is not exceeded, store new file
        if (
            ($user->getStorageSizeInBytes() - ($this->getFilledStorageSpace() + $file->getSize())) >= 0 ||
            $user->role === RolesEnum::ADMIN->value
        ) {
            if ($file->storeAs($user->id, $filename)) {
                // if file is an image, create the thumbnail
                if (in_array($file->getClientMimeType() , ['image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/jpg'])) {
                    $thumbnail = Image::make($file);
                    $thumbnail->fit(140, 140);
                    $thumbnail->save(Storage::path($user->id . '/thumbnail/') . $filename  . '.' . $file->getClientOriginalExtension());
                }

                return File::create([
                    'user_id' => $user->id,
                    'folder_id' => $folder?->id,
                    'original_name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                    'encoded_name' => $filename,
                    'size' => $file->getSize(),
                    'mime' => $file->getClientMimeType(),
                    'extension' => $file->getClientOriginalExtension()
                ]);
            } else {
                return response()->json([
                    'errors' => [
                        'file' => [
                            'Ошибка при размещении файла в хранилище'
                        ]
                    ],
                    'status' => true
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        } else {
            return response()->json([
                'errors' => [
                    'file' => [
                        'Превышен размер хранилища'
                    ]
                ],
                'status' => true
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Updates the original name of the file
     *
     * @param File $file
     * @param string $name
     * @return bool
     */
    public function update(File $file, string $name): bool
    {
        return $file->update(['original_name' => $name]);
    }

    /**
     * Deletes the file from storage and database
     *
     * @param File $file
     * @return bool|null
     */
    public function delete(File $file): ?bool
    {
        Storage::delete(((string) auth()->id()) . '/' . $file->encoded_name);
        Storage::delete(((string) auth()->id()) . '/thumbnail/' . $file->encoded_name . '.' . $file->extension);
        return $file->delete();
    }

    /**
     * Returns the size of all user's file in bytes
     *
     * @return int|float
     */
    public function getFilledStorageSpace(): int|float
    {
        return File::query()
            ->where('user_id', auth()->id())
            ->sum('size');
    }
}
