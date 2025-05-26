@extends('layouts.admin')

@section('content')

<a href="{{route('vk.redirect')}}" class="btn btn-danger btn-block" target="_blank">Login with VK</a>
<form action="{{ route('vk.parseurl')}}" method="POST">
    @csrf
    <input class="border border-gray-300" name="url"/>
    <button>Send</button>
</form>

@endsection
