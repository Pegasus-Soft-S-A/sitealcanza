@extends('plugins/ecommerce::orders.master')
@section('title')
    {{ __('Checkout') }}
@stop

@section('content')

    @if (is_plugin_active('payment'))
        @include('plugins/payment::partials.header')
    @endif

    {!! Form::open([
        'route' => ['public.checkout.process', $token],
        'class' => 'checkout-form payment-checkout-form',
        'id' => 'checkout-form',
    ]) !!}
    <input type="hidden" name="checkout-token" id="checkout-token" value="{{ $token }}">
    <div class="container" id="main-checkout-product-info">
        <div class="row">
            <div class="order-1 order-md-2 col-lg-5 col-md-6 right">
                <div class="d-block d-sm-none">
                    @include('plugins/ecommerce::orders.partials.logo')
                </div>
                <div id="cart-item" class="position-relative">

                    <div class="payment-info-loading" style="display: none;">
                        <div class="payment-info-loading-content">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </div>

                    {!! apply_filters(RENDER_PRODUCTS_IN_CHECKOUT_PAGE, $products) !!}

                    @php
                        $rawTotal = Cart::instance('cart')->rawTotal();
                        $orderAmount = max($rawTotal - $promotionDiscountAmount - $couponDiscountAmount, 0);
                        $orderAmount += (float) $shippingAmount;
                    @endphp

                    <div class="mt-2 p-2">
                        <div class="row">
                            <div class="col-6">
                                <p>{{ __('Subtotal') }}:</p>
                            </div>
                            <div class="col-6">
                                <p class="price-text sub-total-text text-end">
                                    {{ format_price(Cart::instance('cart')->rawSubTotal()) }} </p>
                            </div>
                        </div>
                        @if (EcommerceHelper::isTaxEnabled())
                            <div class="row">
                                <div class="col-6">
                                    <p>{{ __('Tax') }}:</p>
                                </div>
                                <div class="col-6 float-end">
                                    <p class="price-text tax-price-text">
                                        {{ format_price(Cart::instance('cart')->rawTax()) }}</p>
                                </div>
                            </div>
                        @endif
                        @if (session('applied_coupon_code'))
                            <div class="row coupon-information">
                                <div class="col-6">
                                    <p>{{ __('Coupon code') }}:</p>
                                </div>
                                <div class="col-6">
                                    <p class="price-text coupon-code-text"> {{ session('applied_coupon_code') }} </p>
                                </div>
                            </div>
                        @endif
                        @if ($couponDiscountAmount > 0)
                            <div class="row price discount-amount">
                                <div class="col-6">
                                    <p>{{ __('Coupon code discount amount') }}:</p>
                                </div>
                                <div class="col-6">
                                    <p class="price-text total-discount-amount-text">
                                        {{ format_price($couponDiscountAmount) }} </p>
                                </div>
                            </div>
                        @endif
                        @if ($promotionDiscountAmount > 0)
                            <div class="row">
                                <div class="col-6">
                                    <p>{{ __('Promotion discount amount') }}:</p>
                                </div>
                                <div class="col-6">
                                    <p class="price-text"> {{ format_price($promotionDiscountAmount) }} </p>
                                </div>
                            </div>
                        @endif
                        @if (!empty($shipping) && Arr::get($sessionCheckoutData, 'is_available_shipping', true))
                            <div class="row">
                                <div class="col-6">
                                    <p>{{ __('Shipping fee') }}:</p>
                                </div>
                                <div class="col-6 float-end">
                                    <p class="price-text shipping-price-text">{{ format_price($shippingAmount) }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-6">
                                <p><strong>{{ __('Total') }}</strong>:</p>
                            </div>
                            <div class="col-6 float-end">
                                <p class="total-text raw-total-text"
                                    data-price="{{ format_price($rawTotal, null, true) }}">
                                    {{ format_price($orderAmount) }} </p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                {{-- <div class="mt-3 mb-5">
                    @include('plugins/ecommerce::themes.discounts.partials.form')
                </div> --}}
            </div>
            <div class="col-lg-7 col-md-6 left">
                <div class="d-none d-sm-block">
                    @include('plugins/ecommerce::orders.partials.logo')
                </div>
                <div class="form-checkout">
                    @if ($isShowAddressForm)
                        <div>
                            <h5 class="checkout-payment-title">{{ __('Shipping information') }}</h5>
                            <input type="hidden" value="{{ route('public.checkout.save-information', $token) }}"
                                id="save-shipping-information-url">
                            @include(
                                'plugins/ecommerce::orders.partials.address-form',
                                compact('sessionCheckoutData'))
                        </div>
                        <br>
                    @endif

                    @if (EcommerceHelper::isBillingAddressEnabled())
                        <div>
                            <h5 class="checkout-payment-title">{{ __('Billing information') }}</h5>
                            @include(
                                'plugins/ecommerce::orders.partials.billing-address-form',
                                compact('sessionCheckoutData'))
                        </div>
                        <br>
                    @endif

                    <div class="form-group mb-3 @if ($errors->has('description')) has-error @endif">
                        <label for="description" class="control-label">{{ __('Order notes') }}</label>
                        <br>
                        <textarea name="description" id="description" rows="3" class="form-control"
                            placeholder="{{ __('Notes about your order, e.g. special notes for delivery.') }}">{{ old('description') }}</textarea>
                        {!! Form::error('description', $errors) !!}
                    </div>

                    @if (EcommerceHelper::getMinimumOrderAmount() > Cart::instance('cart')->rawSubTotal())
                        <div class="alert alert-warning">
                            {{ __('Minimum order amount is :amount, you need to buy more :more to place an order!', ['amount' => format_price(EcommerceHelper::getMinimumOrderAmount()), 'more' => format_price(EcommerceHelper::getMinimumOrderAmount() - Cart::instance('cart')->rawSubTotal())]) }}
                        </div>
                    @endif

                    @if (is_plugin_active('payment'))
                        <div class="position-relative" style="display: none">
                            <div class="payment-info-loading" style="display: none;">
                                <div class="payment-info-loading-content">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                            </div>

                            <h5 class="checkout-payment-title">{{ __('Payment method') }}</h5>
                            <input type="hidden" name="amount" value="{{ format_price($orderAmount, null, true) }}">
                            <input type="hidden" name="currency"
                                value="{{ strtoupper(get_application_currency()->title) }}">
                            @if (is_plugin_active('payment'))
                                {!! apply_filters(PAYMENT_FILTER_PAYMENT_PARAMETERS, null) !!}
                            @endif
                            <ul class="list-group list_payment_method">
                                @if ($orderAmount)
                                    {!! apply_filters(PAYMENT_FILTER_ADDITIONAL_PAYMENT_METHODS, null, [
                                        'amount' => format_price($orderAmount, null, true),
                                        'currency' => strtoupper(get_application_currency()->title),
                                        'name' => null,
                                        'selected' => PaymentMethods::getSelectedMethod(),
                                        'default' => PaymentMethods::getDefaultMethod(),
                                        'selecting' => PaymentMethods::getSelectingMethod(),
                                    ]) !!}

                                    {!! PaymentMethods::render() !!}
                                @endif
                            </ul>
                        </div>
                    @else
                        <input type="hidden" name="amount" value="{{ format_price($orderAmount, null, true) }}">
                    @endif

                    <div class="form-group mb-3">
                        <div class="row">
                            <div class="col-md-6 d-none d-md-block" style="line-height: 53px">
                                <a class="text-info" href="{{ route('public.cart') }}"><i
                                        class="fas fa-long-arrow-alt-left"></i> <span
                                        class="d-inline-block back-to-cart">{{ __('Back to cart') }}</span></a>
                            </div>

                            <div class="col-md-6 checkout-button-group">
                                <button type="button" onclick="openPaybox();"
                                    class="btn payment-checkout-btn-step float-end"
                                    data-processing-text="{{ __('Processing. Please wait...') }}">
                                    {{ __('Checkout') }}
                                </button>
                            </div>
                        </div>


                        <div class="d-block d-md-none back-to-cart-button-group">
                            <a class="text-info" href="{{ route('public.cart') }}">
                                <i class="fas fa-long-arrow-alt-left"></i>
                                <span class="d-inline-block">{{ __('Back to cart') }}</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <button type="button" id="buttonPayPlux" style="display: none;">Pagar con tarjeta</button>

    {!! Form::close() !!}

    @if (is_plugin_active('payment'))
        @include('plugins/payment::partials.footer')
    @endif
@stop

@push('header')
    <link rel="stylesheet" href="{{ asset('vendor/core/core/base/libraries/intl-tel-input/css/intlTelInput.min.css') }}">

    {{-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> --}}

    <script type="text/javascript">
        var data = {
            /* Requerido. Email de la cuenta PagoPlux del Establecimiento o Id/Class
            del elemento html que posee el valor */
            PayboxRemail: "jaime@pegasus.ec",
            /* Requerido. Email del usuario que realiza el pago o Id/Class del
            elemento html que posee el valor */
            PayboxSendmail: "{{ $currentCustomer->email }}",
            /* Requerido. Nombre del establecimiento en PagoPlux o Id/Class del
            elemento html que posee el valor */
            PayboxRename: "Jaime Sarabia",
            /* Requerido. Nombre del usuario que realiza el pago o Id/Class del
            elemento html que posee el valor */
            PayboxSendname: "{{ $currentCustomer->name }}",

            /* Requerido. Ejemplo: 100.00, 10.00, 1.00 o Id/Class del elemento html
            que posee el valor de los productos sin impuestos */
            PayboxBase0: "0",
            /* Requerido. Ejemplo: 100.00, 10.00, 1.00 o Id/Class del elemento html
            que posee el valor de los productos con su impuesto incluido */
            PayboxBase12: "{{ $orderAmount }}",
            /* Requerido. Descripción del pago o Id/Class del elemento html que posee
            el valor */
            PayboxDescription: "Orden de compra {{ $order->code }}",
            /* Requerido Tipo de Ejecución
            * Production: true (Modo Producción, Se procesarán cobros y se
            cargarán al sistema, afectará a la tdc)
            * Production: false (Modo Prueba, se realizarán cobros de prueba y no
            se guardará ni afectará al sistema)
            */
            PayboxProduction: false,
            /* Requerido Ambiente de ejecución
            * prod: Modo Producción, Se procesarán cobros y se cargarán al sistema,
            afectará a la tdc.
            * sandbox: Modo Prueba, se realizarán cobros de prueba
            */
            PayboxEnvironment: "sandbox",
            /* Requerido. Lenguaje del Paybox
             * Español: es | (string) (Paybox en español)
             */
            PayboxLanguage: "es",
            /* Opcional Valores HTML que son requeridos por la web que implementa
            el botón de pago.
            * Se permiten utilizar los identificadores de # y . que describen los
            Id y Class de los Elementos HTML
            * Array de identificadores de elementos HTML |
            Ejemplo: PayboxRequired: ["#nombre", "#correo", "#monto"]
            */
            PayboxRequired: [],
            /*
             * Requerido. dirección del tarjetahabiente o Id/Class del elemento
             * html que posee el valor
             */
            PayboxDirection: "{{ Arr::get($sessionCheckoutData, 'address') ?? 'S/N' }}",
            /*
             * Requerido. Teléfono del tarjetahabiente o Id/Class del elemento
             * html que posee el valor
             */
            PayBoxClientPhone: "{{ $currentCustomer->phone }}",

            /* Opcionales
            * Solo aplica para comercios que tengan habilitado pagos
            internacionales
            */
            PayBoxClientName: '{{ $currentCustomer->name }}',
            PayBoxClientIdentification: '{{ $currentCustomer->identificacion }}',
            /* Opcional
            * true ->
            Se usa en TRUE cuando se necesita enlazar el paybox a un botón ya existen
            te en el sitio del cliente, caso contrario. NOTA: Valor defecto false
            */
            PayboxPagoPlux: true,
            /* Opcional
            * Es requerido solo en el caso de tener PayboxPagoPlux en true se debe
            especificar el elemento HTML al cual se anclará el click para levantar el
            Paybox
            */
            PayboxIdElement: 'buttonPayPlux',
        };
    </script>

    <script type="text/javascript">
        var onAuthorize = function(response) {
            // La variable response posee un Objeto con la respuesta de PagoPlux.
            if (response.status == 'succeeded') {

                console.log(response.detail);

                const button = document.getElementById('buttonCustomPaybox');
                const formPay = document.getElementById('checkout-form');

                formPay.submit();

            } else {
                console.log(response)
            }
        };
    </script>

    <script src="https://sandbox-paybox.pagoplux.com/paybox/index.js"></script>
@endpush

@push('footer')
    <script src="{{ asset('vendor/core/core/base/libraries/intl-tel-input/js/intlTelInput.min.js') }}"></script>
    <script src="{{ asset('vendor/core/core/base/js/phone-number-field.js') }}"></script>

    <script type="text/javascript">
        function openPaybox() {
            const btn = document.getElementById('buttonPayPlux');
            btn.click();
        }
    </script>
@endpush
