@extends('layouts.admin')

@section('content')

<table class="table-auto">
    <thead class="text-xs">
        <tr>
            <th class="px-6 py-3">Дата</th>
            <th class="px-6 py-3">IP, ULID</th>
            <th class="px-6 py-3">Ali ID</th>
            <th class="px-6 py-3">Категория</th>
            <th class="px-6 py-3">Цена</th>
            <th class="px-6 py-3">utm_source</th>
            <th class="px-6 py-3">utm_medium</th>
            <th class="px-6 py-3">referer</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($clicks as $click)

        <tr>
            <td class="text-xs px-6 py-3">
                {{ $click->transition_at }}
            </td>
            <td class="text-xs px-6 py-3">
                {{ $click->user_ip }}<br >
                {{ $click->user_ulid }}
            </td>
            <td class="px-6 py-3">
                {{ $click->product->name ?? ''}} <br >
                <span class="text-xs">{{ $click->ali_id }}</span>
            </td>
            <td class="px-6 py-3">{{ $click->product->alicat_id ?? '' }}</td>
            <td class="px-6 py-3">{{ $click->price }}</td>
            <td class="px-6 py-3">{{ $click->utm_source }}</td>
            <td class="px-6 py-3">{{ $click->utm_medium }}</td>
            <td class="px-6 py-3">{{ $click->referer }}</td>
        </tr>
        @endforeach
    </tbody>

</table>

{{ $clicks->links() }}

@endsection
