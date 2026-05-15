<?php

namespace T_Shop\Controllers;

use T_Shop\Services\ChapterService;
use T_Shop\Requests\StoreChapterRequest;
use T_Shop\Responses\JsonResponse;

class ChapterController
{
    protected $service;

    public function __construct()
    {
        $this->service = new ChapterService();
    }

    public function store()
    {
        $validation = StoreChapterRequest::validate(
            $_POST
        );

        if (!$validation['success']) {

            return JsonResponse::error(
                $validation['errors']
            );
        }

        try {

            $chapterId = $this->service->create(
                $_POST
            );

            return JsonResponse::success([
                'chapter_id' => $chapterId
            ]);

        } catch (\Exception $e) {

            return JsonResponse::error([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy()
    {
        $id = (int) $_POST['id'];

        $this->service->delete($id);

        return JsonResponse::success();
    }
}