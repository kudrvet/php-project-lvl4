<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStatusesStoreRequest;
use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class TaskStatusesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $taskStatuses = TaskStatus::paginate(15);
        return  Response::view('task_statuses.index', compact('taskStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $taskStatus = new TaskStatus();
        return response()
            ->view('task_statuses.create', compact('taskStatus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TaskStatusesStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskStatusesStoreRequest $request)
    {
        TaskStatus::create($request->validated());
        flash(__('Статус успешно создан'))->success();
        return redirect()->route('task_statuses.index');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TaskStatus  $taskStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(TaskStatus $taskStatus)
    {
        return \response()->view('task_statuses.edit', compact('taskStatus'));
    }

    /**
     * @param TaskStatusesStoreRequest $request
     * @param TaskStatus $taskStatus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TaskStatusesStoreRequest $request, TaskStatus $taskStatus)
    {
        $taskStatus->fill($request->validated());
        $taskStatus->save();
        flash(__('Статус обновлен'))->success();
        return redirect()->route('task_statuses.index');
    }

    /**
     * @param TaskStatus $taskStatus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(TaskStatus $taskStatus)
    {
        $tasksWithThisStatusCount = $taskStatus->tasks()->count();

        if ($tasksWithThisStatusCount === 0) {
            $taskStatus->delete();
            flash(__('Статус успешно удален'))->success();
        } else {
            flash(__('Не удалось удалить статус'))->warning();
        }

        return redirect()->route('task_statuses.index');
    }
}
