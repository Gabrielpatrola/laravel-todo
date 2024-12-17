<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Enums\TaskStatus;
use App\Http\Requests\CreateOrUpdateTaskRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $tasks = Task::all();
        return response()->json($tasks);
    }

    /**
     * Store a newly created task in storage.
     *
     * @param CreateOrUpdateTaskRequest $request
     * @return JsonResponse
     */
    public function store(CreateOrUpdateTaskRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $task = Task::create($validated);

        return response()->json($task, 201);
    }

    /**
     * Display the specified task.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function show(Task $task): JsonResponse
    {
        return response()->json($task);
    }

    /**
     * Update the specified task in storage.
     *
     * @param CreateOrUpdateTaskRequest $request
     * @param Task $task
     * @return JsonResponse
     */
    public function update(CreateOrUpdateTaskRequest $request, Task $task): JsonResponse
    {
        $validated = $request->validated();

        $task->update($validated);

        return response()->json($task, 200);
    }

    /**
     * Remove the specified task from storage.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function destroy(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json(null, 204);
    }

    /**
     * Toggle the status of the specified task.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function toggleStatus(Task $task): JsonResponse
    {
        $task->status = $task->status === TaskStatus::Pending->value ? TaskStatus::Completed->value : TaskStatus::Pending->value;
        $task->save();

        return response()->json($task, 200);
    }
}
