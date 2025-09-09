@props(['dismissable' => true, 'type' => 'success', 'ariaLabel' => __('Close')])

<div {{ $attributes->merge(['class' => 'alert alert-' . $type]) }} role="alert" style="margin-bottom: 0px !important;">
    @if ($dismissable)
        <button type="button" class="close" data-dismiss="alert" aria-label="{{ $ariaLabel }}">
            <span aria-hidden="true">&times;</span>
        </button>
    @endif
    {{ $slot }}
</div>
