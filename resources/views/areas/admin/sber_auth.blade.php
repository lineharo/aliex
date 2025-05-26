@extends('layouts.admin')

@section('content')

@if ($isAuth)
    Ok
@else
    <a href="{{route('sber.gettoken')}}" class="btn btn-danger btn-block">Sber get token</a>
@endif

@endsection
