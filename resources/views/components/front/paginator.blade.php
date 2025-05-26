@php
    if ($paginator->lastPage() == 1) {
        return null;
    }
@endphp

{{-- Первая --}}
@php $first = '' @endphp
@if (!$paginator->onFirstPage())
    @php
        if ($paginator->currentPage() == 2) {
            $rel = ' rel="prev"';
        } else {
            $rel = '';
        }
        $first .= '<a class="paginator__first" href="'. $paginator->url(1) .'"'.$rel.'>';
        $first .= '1';
        $first .= '</a>';
    @endphp
@endif

{{-- 5 до текущей --}}
@php $before = '' @endphp
@for ($x = 5; $x >= 1 ; $x--)
    @if ($paginator->currentPage() - $x > 1)
        @php
            if ($x == 1) {
                $rel = ' rel="prev"';
            } else {
                $rel = '';
            }
            $before .= '<a class="paginator__before px-'. 5-$x+1 . '" href="' . $paginator->url($paginator->currentPage() - $x) . '"'.$rel.'>';
            $before .= $paginator->currentPage() - $x;
            $before .= '</a>';
        @endphp
    @endif
@endfor

{{-- Текущая --}}
@php
    $current = '<span class="paginator__current" aria-current="page">';
    $current .= $paginator->currentPage();
    $current .= '</span>';
@endphp

{{-- 5 после текущей --}}
@php $after = '' @endphp
@for ($x = 1; $x < 5 ; $x++)
    @if ($paginator->currentPage() + $x < $paginator->lastPage())
        @php
            if ($x == 1) {
                $rel = ' rel="next"';
            } else {
                $rel = '';
            }
            $after .= '<a class="paginator__after px-' . 5-$x+1 . '" href="' . $paginator->url($paginator->currentPage() + $x) . '"'.$rel.'>';
            $after .= $paginator->currentPage() + $x;
            $after .= '</a>';
        @endphp
    @endif
@endfor

{{-- Последняя --}}
@php $last = '' @endphp
@if ($paginator->lastPage() != $paginator->currentPage())
    @php
        if ($paginator->lastPage() - $paginator->currentPage() == 1) {
            $rel = ' rel="next"';
        } else {
            $rel = '';
        }
        $last .= '<a class="paginator__last" href="' . $paginator->url($paginator->lastPage()) . '"'.$rel.'>';
        $last .= $paginator->lastPage();
        $last .= '</a>';
    @endphp
@endif


<nav class="paginator" aria-label="Pagination">
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
</nav>