<div class="ps-my-account">
    <div class="container">
        <form id="formulario" class="ps-form--account ps-tab-root" method="POST"
            action="{{ route('customer.register.post') }}">
            @csrf
            <div class="ps-form__content">
                <h4>{{ __('Register An Account') }}</h4>
                <div class="form-group">
                    <input class="form-control" name="identificacion" id="txt-identificacion" type="text"
                        value="{{ old('identificacion') }}" onblur="validarIdentificacion()"
                        placeholder="IDENTIFICACIÓN">
                    @if ($errors->has('identificacion'))
                        <span class="text-danger small">{{ $errors->first('identificacion') }}</span>
                    @endif
                    <span class="text-danger d-none" id="mensajeBandera">La identificacion no es válida</span>
                    <span class="text-danger d-none" id="mensajeTipo">La identificacion para un vendedor debe ser
                        RUC</span>
                </div>
                <div class="form-group">
                    <input class="form-control" name="name" id="txt-name" type="text" value="{{ old('name') }}"
                        placeholder="{{ __('Your Name') }}">
                    @if ($errors->has('name'))
                        <span class="text-danger small">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <input class="form-control" name="email" id="txt-email" type="email" value="{{ old('email') }}"
                        autocomplete="email" placeholder="{{ __('Your Email') }}">
                    @if ($errors->has('email'))
                        <span class="text-danger small">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <input class="form-control" type="password" name="password" id="txt-password"
                        autocomplete="new-password" placeholder="{{ __('Password') }}">
                    @if ($errors->has('password'))
                        <span class="text-danger small">{{ $errors->first('password') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <input class="form-control" type="password" name="password_confirmation"
                        id="txt-password-confirmation" autocomplete="new-password"
                        placeholder="{{ __('Password Confirmation') }}">
                    @if ($errors->has('password_confirmation'))
                        <span class="text-danger small">{{ $errors->first('password_confirmation') }}</span>
                    @endif
                </div>
                @if (is_plugin_active('marketplace'))
                    <div class="show-if-vendor" @if (old('is_vendor') == 0) style="display: none" @endif>
                        <div class="form-group">
                            <label for="shop-name" class="required">{{ __('Shop Name') }}</label>
                            <input class="form-control" name="shop_name" id="shop-name" type="text"
                                value="{{ old('shop_name') }}" placeholder="{{ __('Shop Name') }}">
                            @if ($errors->has('shop_name'))
                                <span class="text-danger small">{{ $errors->first('shop_name') }}</span>
                            @endif
                        </div>
                        <div class="form-group shop-url-wrapper">
                            <label for="shop-url" class="required float-left">{{ __('Shop URL') }}</label>
                            <span class="d-inline-block float-right shop-url-status"></span>
                            <input class="form-control" name="shop_url" id="shop-url" type="text"
                                value="{{ old('shop_url') }}" placeholder="{{ __('Shop URL') }}"
                                data-url="{{ route('public.ajax.check-store-url') }}">
                            @if ($errors->has('shop_url'))
                                <span class="text-danger small">{{ $errors->first('shop_url') }}</span>
                            @else
                                <span class="d-inline-block"><small
                                        data-base-url="{{ route('public.store', '') }}">{{ route('public.store', (string) old('shop_url')) }}</small></span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="shop-phone" class="required">{{ __('Phone Number') }}</label>
                            <input class="form-control" name="shop_phone" id="shop-phone" type="text"
                                value="{{ old('shop_phone') }}" placeholder="{{ __('Shop phone') }}">
                            @if ($errors->has('shop_phone'))
                                <span class="text-danger small">{{ $errors->first('shop_phone') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group user-role">
                        <p>
                            <label>
                                <input type="radio" name="is_vendor" value="0"
                                    @if (old('is_vendor') == 0) checked="checked" @endif>
                                <span class="d-inline-block">
                                    {{ __('I am a customer') }}
                                </span>
                            </label>
                        </p>
                        <p>
                            <label>
                                <input type="radio" name="is_vendor" value="1"
                                    @if (old('is_vendor') == 1) checked="checked" @endif>
                                <span class="d-inline-block">
                                    {{ __('I am a vendor') }}
                                </span>
                            </label>
                        </p>
                    </div>
                @endif
                <div class="form-group">
                    <p>{{ __('Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our privacy policy.') }}
                    </p>
                </div>
                <div class="form-group">
                    <div class="ps-checkbox">
                        <input type="hidden" name="agree_terms_and_policy" value="0">
                        <input class="form-control" type="checkbox" name="agree_terms_and_policy"
                            id="agree-terms-and-policy" value="1"
                            @if (old('agree_terms_and_policy') == 1) checked @endif>
                        <label for="agree-terms-and-policy">{{ __('I agree to terms & Policy.') }}</label>
                    </div>
                    @if ($errors->has('agree_terms_and_policy'))
                        <span class="text-danger small">{{ $errors->first('agree_terms_and_policy') }}</span>
                    @endif
                </div>

                @if (is_plugin_active('captcha') &&
                        setting('enable_captcha') &&
                        get_ecommerce_setting('enable_recaptcha_in_register_page', 0))
                    <div class="form-group">
                        {!! Captcha::display() !!}
                    </div>
                @endif

                <div class="form-group submit">
                    <button class="ps-btn ps-btn--fullwidth" type="submit">{{ __('Sign up') }}</button>
                </div>

                <div class="form-group">
                    <p class="text-center">{{ __('Already have an account?') }} <a
                            href="{{ route('customer.login') }}" class="d-inline-block">{{ __('Log in') }}</a></p>
                </div>
            </div>
            <div class="ps-form__footer">
                {!! apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, \Botble\Ecommerce\Models\Customer::class) !!}
            </div>
        </form>
    </div>
</div>

<script>
    var enviar = true;

    function validarIdentificacion() {

        var cad = document.getElementById('txt-identificacion').value.trim();
        var total = 0;
        var longitud = cad.length;
        var longcheck = longitud - 1;
        var digitos = cad.split('').map(Number);
        var codigo_provincia = digitos[0] * 10 + digitos[1];
        if (cad !== "" && longitud === 10) {

            if (cad != '2222222222' && codigo_provincia >= 1 && (codigo_provincia <= 24 || codigo_provincia == 30)) {
                for (i = 0; i < longcheck; i++) {
                    if (i % 2 === 0) {
                        var aux = cad.charAt(i) * 2;
                        if (aux > 9) aux -= 9;
                        total += aux;
                    } else {
                        total += parseInt(cad.charAt(i));
                    }
                }
                total = total % 10 ? 10 - total % 10 : 0;

                if (cad.charAt(longitud - 1) == total) {
                    recuperarInformacion(cad);
                    $('#mensajeBandera').addClass("d-none");
                    $('#txt-identificacion').removeClass("is-invalid");
                    enviar = true;

                } else {
                    $('#mensajeBandera').removeClass("d-none");
                    $('#txt-identificacion').addClass("is-invalid");
                    camposvacios();
                    enviar = false;
                }
            } else {
                $('#mensajeBandera').removeClass("d-none");
                $('#txt-identificacion').addClass("is-invalid");
                enviar = false;

            }
        } else
        if (longitud == 13 && cad !== "") {
            var extraer = cad.substr(10, 3);
            if (extraer == "001") {
                recuperarInformacion(cad);
                $('#mensajeBandera').addClass("d-none");
                $('#txt-identificacion').removeClass("is-invalid");
                enviar = true;
            } else {

                $('#mensajeBandera').removeClass("d-none");
                $('#txt-identificacion').addClass("is-invalid");
                enviar = false;
            }


        } else
        if (cad !== "") {

            $('#mensajeBandera').removeClass("d-none");
            $('#txt-identificacion').addClass("is-invalid");
            camposvacios();
            enviar = false;
        }

    }

    function camposvacios() {

        $("#txt-name").val("");
        $("#txt-email").val("");
    }

    function recuperarInformacion(cad) {

        $.ajax({
            url: 'https://perseo.app/api/datos/datos_consulta',
            headers: {
                'Usuario': 'perseo',
                'Clave': 'Perseo1232*'
            },
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                identificacion: cad
            },
            success: function(data) {
                data = JSON.parse(data);
                if (data.identificacion) {
                    $("#txt-name").val(data.razon_social);
                    $("#txt-email").val(data.correo);
                
                }
            }
        });
    }




    document.getElementById("formulario").addEventListener("submit", function(event) {
        if (enviar == false) {
            event.preventDefault();
        }
        var cad = document.getElementById('txt-identificacion').value.trim();
        var longitud = cad.length;
        var tipo = document.querySelector('input[name="is_vendor"]:checked').value;

    
        if (longitud == 10 && tipo == 1) {
            event.preventDefault();
            $('#mensajeTipo').removeClass("d-none");
            $('#txt-identificacion').addClass("is-invalid");
        } else {
            $('#mensajeTipo').addClass("d-none");
            $('#txt-identificacion').removeClass("is-invalid");
        }



    });
</script>
