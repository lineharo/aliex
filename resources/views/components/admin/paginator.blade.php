@php
    if ($paginator->lastPage() == 1) {
        return null;
    }
@endphp

{{-- Первая --}}
@php $first = '' @endphp
@if (!$paginator->onFirstPage())
    @php
        $first .= '<a class="paginator__first" href="'. $paginator->url(1) .'">';
        $first .= '1';
        $first .= '</a>';
    @endphp
@endif

{{-- 5 до текущей --}}
@php $before = '' @endphp
@for ($x = 15; $x >= 1 ; $x--)
    @if ($paginator->currentPage() - $x > 1)
        @php
            $before .= '<a class="paginator__before px-'. 5-$x+1 . '" href="' . $paginator->url($paginator->currentPage() - $x) . '">';
            $before .= $paginator->currentPage() - $x;
            $before .= '</a>';
        @endphp
    @endif
@endfor

{{-- Текущая --}}
@php
    $current = '<span class="paginator__current">';
    $current .= $paginator->currentPage();
    $current .= '</span>';
@endphp

{{-- 5 после текущей --}}
@php $after = '' @endphp
@for ($x = 1; $x < 15 ; $x++)
    @if ($paginator->currentPage() + $x < $paginator->lastPage())
        @php
            $after .= '<a class="paginator__after px-' . 5-$x+1 . '" href="' . $paginator->url($paginator->currentPage() + $x) . '">';
            $after .= $paginator->currentPage() + $x;
            $after .= '</a>';
        @endphp
    @endif
@endfor

{{-- Последняя --}}
@php $last = '' @endphp
@if ($paginator->lastPage() != $paginator->currentPage())
    @php
        $last .= '<a class="paginator__last" href="' . $paginator->url($paginator->lastPage()) . '">';
        $last .= $paginator->lastPage();
        $last .= '</a>';
    @endphp
@endif


<div class="paginator">
    {!! $first !!}
    @if($first)
        <div class="paginator__separator"></div>
    @endif
    {!! $before !!}
    {!! $current !!}
    {!! $after !!}
    @if($last)
        <div class="paginator__separator"></div>
    @endif
    {!! $last !!}
</div>