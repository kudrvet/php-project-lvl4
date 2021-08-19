<?php
use App\Models\Label;

/**
 * @var Label[] $labels
 */
?>

@extends('layouts.app')

@section('content')
    <main class="container py-4">
        <h1 class="mb-5">@lang('метки')</h1>
        @auth()
        <a href="{{route('labels.create')}}" class="btn btn-primary"> @lang('Создать метку') </a>
        @endauth
        <table class="table mt-2">
            <thead>
            <tr>
                <th>@lang('ID')</th>
                <th>@lang('Имя')</th>
                <th>@lang('Дата создания')</th>
                @auth
                <th>@lang('Действия')</th>
                @endauth
            </tr>
            </thead>
            <tbody>
            @foreach($labels as $label)
                <tr>
                    <td>{{$label->id}}</td>
                    <td>{{$label->name}}</td>
                    <td>{{$label->created_at}}</td>
                    @auth
                    <td>
                        <a class="text-danger"
                           href="{{route('labels.destroy', [$label->id])}}"
                           data-confirm="Вы уверены?" data-method="delete"> @lang('Удалить')
                        </a>
                        <a href="{{route('labels.edit',[$label->id])}}" > @lang('Изменить') </a>
                    </td>
                    @endauth
                </tr>
            @endforeach
            </tbody>
        </table>
    </main>
@endsection