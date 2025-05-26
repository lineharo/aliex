@extends('layouts.admin')

@section('content')

<table class="table-auto">
    <thead>
        <tr>
            <th class="px-4 py-2">ID</th>
            <th class="px-4 py-2">Date</th>
            <th class="px-4 py-2">Query</th>
            <th class="px-4 py-2">Results count</th>
            <th class="px-4 py-2">IP</th>
            <th class="px-4 py-2">User Agent</th>
            <th class="px-4 py-2">Referer</th>
        </tr>
    </thead>
    <tbody>
        @foreach($searches as $search)
        <tr>
            <td class="border px-4 py-2">{{ $search->id }}</td>
            <td class="border px-4 py-2">{{ $search->created_at }}</td>
            <td class="border px-4 py-2">{{ $search->query }}</td>
            <td class="border px-4 py-2">{{ $search->results_count }}</td>
            <td class="border px-4 py-2">{{ $search->ip }}</td>
            <td class="border px-4 py-2">{{ $search->user_agent }}</td>
            <td class="border px-4 py-2">{{ $search->referer }}</td>
        </tr>
        @endforeach

</table>

{{ $searches->links('components.admin.paginator') }}

@endsection