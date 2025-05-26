<div class="margins mb-4">
    <div class="breadcrumbs">
        <div class="breadcrumbs__wrap" itemscope itemtype="https://schema.org/BreadcrumbList">
            @if($showHome)
                <div class="breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a itemprop="item" href="/">
                    <span itemprop="name">Скидки Ali-ex</span></a>
                    <meta itemprop="position" content="1">
                </div>
                @if(count($items) > 0)
                    <div class="breadcrumbs__arrow">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 7L15 12L10 17" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        </svg>
                    </div>
                @endif
            @endif
            @foreach ($items as $item)
                <div class="breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    @if($loop->last)
                        <span itemprop="name">{{ $item['name'] }}</span>
                    @else
                        <a itemprop="item" href="{{ $item['url'] }}">
                            <span itemprop="name">{{ $item['name'] }}</span>
                        </a>
                    @endif
                    <meta itemprop="position" content="{{ $showHome ? $loop->index + 2 : $loop->index }}">
                </div>
                @if(!$loop->last)
                    <div class="breadcrumbs__arrow">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 7L15 12L10 17" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        </svg>
                    </div>
                @endif
            @endforeach
            </div>
    </div>
</div>
