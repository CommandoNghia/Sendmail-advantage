<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UploadImageRequest;
use App\Models\User;
use App\Services\FileService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class FileController extends Controller
{
    private FileService $fileService;

    /**
     * FileController constructor.
     *
     * @param FileService $fileService
     */
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * @param StoreImageRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function storeImage(StoreImageRequest $request)
    {
        try {
            $file = $request->file('file');
            $upload = $this->fileService->storeImageToS3($file);

            return $this->success($upload);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function upload(Request $request): JsonResponse
    {
        app()->make(UploadImageRequest::class);

        $user = User::find(1);

        $media = $user->addMediaFromRequest('file')
            ->setFileName($request->file('file')->hashName())
            ->toMediaCollection('tmp');

        return $this->success([
            'file_name' => $media->getAttribute('file_name'),
            'uuid' => $media->getAttribute('uuid'),
            'url' => $media->getFullUrl()
        ]);
    }

}
