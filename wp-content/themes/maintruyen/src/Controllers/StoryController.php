<?php

namespace T_Shop\Controllers;

use T_Shop\Services\StoryService;
use T_Shop\Requests\StoreStoryRequest;
use T_Shop\Responses\JsonResponse;

class StoryController
{
    protected $service;

    public function __construct()
    {
        $this->service = new StoryService();
    }

    public function index()
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));

        return JsonResponse::success(
            $this->service->list($page)
        );
    }

    public function store()
    {
        $validation = StoreStoryRequest::validate($_POST);

        if (!$validation['success']) {

            return JsonResponse::error(
                $validation['errors']
            );
        }

        $storyId = $this->service->create($_POST);

        return JsonResponse::success([
            'story_id' => $storyId
        ]);
    }

    public function update()
    {
        $id = (int) $_POST['id'];

        $this->service->update($id, $_POST);

        return JsonResponse::success();
    }

    public function destroy()
    {
        $id = (int) $_POST['id'];

        $this->service->delete($id);

        return JsonResponse::success();
    }
}