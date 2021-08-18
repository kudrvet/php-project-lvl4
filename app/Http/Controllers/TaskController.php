<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\TaskRequest;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class TaskController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'update', 'store','edit','destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::all();

        return response()->view('tasks.index', compact('tasks'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        return response()->view('tasks.show', compact('task'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task();
        $statusesList = ['' => 'Выберите статус'] + TaskStatus::pluck('name', 'id')->all();
        $usersList = ['' => 'Выберите исполнителя'] + User::pluck('name', 'id')->all();
        return response()
            ->view('tasks.create', compact('task', 'statusesList', 'usersList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request)
    {
        $data = $request->input() + ['created_by_id' => \Auth::id()];
        Task::create($data);
        flash(__('Задача успешно создана'))->success();
        return redirect()->route('tasks.index');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $statusesList = ['' => 'Выберите статус'] + TaskStatus::pluck('name', 'id')->all();
        $usersList = ['' => 'Выберите исполнителя'] + User::pluck('name', 'id')->all();
        return \response()->view('tasks.edit', compact('task', 'statusesList', 'usersList'));
    }

    /**
     * @param TaskRequest $request
     * @param Task $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TaskRequest $request, Task $task)
    {
        $task->fill($request->input());
        $task->save();
        flash(__('Статус обновлен'))->success();
        return \redirect()->route('tasks.index');
    }

    /**
     * @param Task $task
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Task $task)
    {
        if (! Gate::allows('destroy-task', $task)) {
//            abort(403, 'You can delete only own tasks');
            throw new AccessDeniedHttpException('You can delete only own tasks');
        }
        $task->delete();
        flash(__('Задача успешно удалена'))->success();
        return \redirect()->route('tasks.index');
    }
}
