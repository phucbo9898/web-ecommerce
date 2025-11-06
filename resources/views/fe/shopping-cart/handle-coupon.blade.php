@if (count(\Cart::session(auth()->id())->getContent()) > 0)
<div>
    <hr style="padding: 0; margin: 1rem 0;">
    @if (count(\Cart::session(auth()->id())->getConditions()) > 0)
        <div class="d-flex justify-content-between">
            @lang('Before the discount'):
            <div>
                {{ number_format(\Cart::session(Auth::id())->getSubTotal()) }}
                @lang('VND')
            </div>
        </div>
        <hr style="padding: 0; margin: 1rem 0;">
        <div class="d-flex justify-content-between">
            @lang('Voucher'):
            <div>
                @dd(\Cart::session(auth()->id())->getConditions()['coupon'])
                @foreach (\Cart::session(auth()->id())->getConditions() as $coupon)
                    @dump($coupon->getAttributes())
                    <div class="mb-1 d-block">
                        <span>{{ $coupon->getName() . ' (' . $coupon->getValue() . ')' }}</span>
                        form
                        <a href="" class="btn btn-danger">x</a>
                    </div>
                @endforeach
            </div>
        </div>
        <hr style="padding: 0; margin: 1rem 0;">
        <div class="d-flex justify-content-between">
            @lang('Total')
            <span>
                {{ number_format(\Cart::session(Auth::id())->getTotal()) }}
                @lang('VND')
            </span>
        </div>
    @else
        <div class="d-flex justify-content-between">
            @lang('Total')
            <span>
                {{ number_format(\Cart::session(Auth::id())->getTotal(), 0, ',', '.') }}
                @lang('VND')
            </span>
        </div>
    @endif
    <hr style="padding: 0; margin: 1rem 0;">
</div>
@else
<div class="d-flex justify-content-between">
    @lang('Total')
    <span>
        {{ number_format(\Cart::session(Auth::id())->getTotal() ?? 0, 0, ',', '.') }}
        @lang('VND')
    </span>
</div>
@endif
