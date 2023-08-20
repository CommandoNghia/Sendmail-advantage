<?php


namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class FileService
{
    /**
     * @param UploadedFile $file
     *
     * @return array
     */
    public function storeImageToS3(UploadedFile $file): array
    {
        $filePath = 'images';
        $path = Storage::cloud()->put($filePath, $file);
        $url = Storage::cloud()->url($path);
        return  ['url' => $url];
    }
}
