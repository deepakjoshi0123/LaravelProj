<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Services\TaskService;

use App\Models\Project;
use App\Models\Proj_Mem;
use App\Models\Comment;
use App\Models\Status;
use App\Models\Task_Mem;
use App\Models\Task;
use App\Models\Member;

class TaskController extends Controller
{
    protected $projectService;
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function addTask(Request $req, $project_id)
    {
        $fls = [];
        $extensionFlag = false;
        $sizeFlag = false;
        $allowedExtension = ["jpg", "jpeg", "png"];
        if ($req["files"]) {
            foreach ($req["files"] as $file) {
                $extension = $file->getClientOriginalExtension();
                if ($file->getSize() > 150000) {
                    $sizeFlag = true;
                }
                if (!in_array($extension, $allowedExtension)) {
                    $extensionFlag = true;
                }
            }
        }

        $validated = Validator::make(json_decode($req["data"], true), [
            "data.title" => "required|string|min:3|max:40|",
            "data.description" => "required|string|min:3|max:200",
            "comments" => "nullable",
            "data.status_id" => "nullable|string",
        ]);

        if ($validated->fails()) {
            return response()->json(
                [
                    $validated->messages(),
                    "sizeFlag" => $sizeFlag,
                    "extensionFlag" => $extensionFlag,
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
        if ($sizeFlag === true || $extensionFlag === true) {
            return response()->json(
                [
                    $validated->messages(),
                    "sizeFlag" => $sizeFlag,
                    "extensionFlag" => $extensionFlag,
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
        return response()->json($this->taskService->addTask($req, $project_id));
    }

    public function updateTask(Request $req, $project_id, $task_id)
    {
        $fls = [];
        $extensionFlag = false;
        $sizeFlag = false;
        $allowedExtension = ["jpg", "jpeg", "png"];
        if ($req["files"]) {
            foreach ($req["files"] as $file) {
                $extension = $file->getClientOriginalExtension();
                if ($file->getSize() > 150000) {
                    $sizeFlag = true;
                }
                if (!in_array($extension, $allowedExtension)) {
                    $extensionFlag = true;
                }
            }
        }

        $validated = Validator::make(json_decode($req["data"], true), [
            "data.title" => "required|string|min:3|max:40|",
            "data.description" => "required|string|min:3|max:200",
            "comments" => "nullable",
            "data.status_id" => "nullable|string",
        ]);

        if ($validated->fails()) {
            return response()->json(
                [
                    $validated->messages(),
                    "sizeFlag" => $sizeFlag,
                    "extensionFlag" => $extensionFlag,
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
        if ($sizeFlag === true || $extensionFlag === true) {
            return response()->json(
                [
                    $validated->messages(),
                    "sizeFlag" => $sizeFlag,
                    "extensionFlag" => $extensionFlag,
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
        return response()->json(
            $this->taskService->updateTask($req, $project_id, $task_id)
        );
    }

    public function assignTask(Request $req)
    {
        return $this->taskService->assignTask($req->all());
    }

    public function delTask(Request $req, $project_id, $task_id)
    {
        return $this->taskService->delTask($req->all(), $project_id, $task_id);
    }

    public function taskMembers(Request $req, $project_id, $task_id)
    {
        return $this->taskService->taskMembers(
            $req->all(),
            $project_id,
            $task_id
        );
    }

    public function taskDetails(Request $req, $project_id, $task_id)
    {
        return response()->json(
            $this->taskService->taskDetails($req->all(), $project_id, $task_id)
        );
    }

    public function projectMembers(Request $req, $project_id)
    {
        return response()->json(
            $this->taskService->projectMembers($req, $project_id)
        );
    }

    public function getTasks(Request $req, $id)
    {
        return response()->json($this->taskService->getTasks($req->all(), $id));
    }
    public function downloadTaskAttachment(
        Request $req,
        $project_id,
        $task_id,
        $file_name
    ) {
        return response()->download("media/" . $file_name);
    }
    public function viewTaskAttachment(
        Request $req,
        $project_id,
        $task_id,
        $file_name
    ) {
        return response()->file("media/" . $file_name);
    }
}
