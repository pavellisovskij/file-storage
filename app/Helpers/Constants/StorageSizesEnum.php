<?php

namespace App\Helpers\Constants;

enum StorageSizesEnum: int
{
    // bytes in 100 Mb
    case STORAGE_SIZE = 104857600;
    // bytes in 10 Mb
    case UPLOAD_FILE_SIZE = 10485760;
    // bytes in 4 Gb
    case MAX = 4294967295;
}
