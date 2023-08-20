<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreImageRequest;
use App\Services\FileService;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

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
     * Upload File to S3 .
     *
     * @param StoreImageRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function storeImage(StoreImageRequest $request)
    {
        try {
            $file = $request->file('file');
            $upload = $this->fileService->storeImageToS3($file);

            return response()->json(['message' => 'Success'], ResponseAlias::HTTP_CREATED);
//            return $this->success($upload);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }
}
