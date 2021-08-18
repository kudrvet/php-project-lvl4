<?php
use App\Models\Task;

/**
 * @var Task[] $tasks
 */
?>

@extends('layouts.app')

@section('content')
    <main class="container py-4">
        <h1 class="mb-5">@lang('Задачи')</h1>
        @auth()
            <a href="{{route('tasks.create')}}" class="btn btn-primary"> @lang('Создать задачу') </a>
        @endauth
        <table class="table mt-2">
            <thead>
            <tr>
                <th>@lang('ID')</th>
                <th>@lang('Статус')</th>
                <th>@lang('Имя')</th>
                <th>@lang('Автор')</th>
                <th>@lang('Исполнитель')</th>
                <th>@lang('Дата создания')</th>
                @auth()
                <th>@lang('Действия')</th>
                @endauth
            </tr>
            </thead>
            <tbody>
            @foreach($tasks as $task)
                <tr>
                    <td>{{$task->id}}</td>
                    <td>{{$task->status->name}}</td>
                    <td>
                        <a href="{{route('tasks.show', [$task->id])}}">{{$task->name}}</a>
                    </td>
                    <td>{{$task->creator->name}}</td>
                    <td>{{optional($task->executor)->name ?? ''}}</td>
                    <td>{{$task->created_at}}</td>
                    @auth()
                        <td>
                            @can('destroy-task',[$task])
                            <a class="text-danger"
                               href="{{route('tasks.destroy', [$task->id])}}"
                               data-confirm="Вы уверены?" data-method="delete"> @lang('Удалить')
                            </a>
                            @endcan

                            <a href="{{route('tasks.edit',[$task->id])}}" > @lang('Изменить') </a>

                        </td>
                    @endauth
                </tr>
            @endforeach
            </tbody>
        </table>
    </main>
@endsection