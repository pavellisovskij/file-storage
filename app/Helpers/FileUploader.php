<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;

class FileUploader
{
    public static function upload(UploadedFile $file)
    {
        $filename = md5($file->getClientOriginalName() . time());

        return $file->storeAs(auth()->id(), $filename);
    }
}
