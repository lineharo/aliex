@props(['tabs'])

<div class="tabs-container">
    <div class="tabs-header">
        @foreach ($tabs as $key => $label)
            <button
                class="tab-button"
                data-tab="{{ $key }}"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>

    <div class="tabs-content">
        @foreach ($tabs as $key => $label)
            <div class="tab-panel" id="tab-{{ $key }}" style="display: none;">
                {{ ${'slot_'.$key} ?? '' }}
            </div>
        @endforeach
    </div>
</div>
