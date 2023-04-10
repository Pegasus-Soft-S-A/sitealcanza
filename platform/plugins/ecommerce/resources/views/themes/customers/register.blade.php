<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="form-border-box">
                <form id="formularioMRegistro" class="form-horizontal" role="form" method="POST"
                    action="{{ route('customer.register.post') }}">
                    <h2 class="normal"><span>{{ __('Register') }}</span></h2>
                    @csrf

                    <div class="form-group{{ $errors->has('identificacion') ? ' has-error' : '' }}">
                        <label for="identificacion" class="col-md-4 control-label">Identificación</label>

                        <div class="col-md-12">
                            <input id="identificacion" type="text" class="form-control" name="identificacion"
                                value="{{ old('identificacion') }}" onblur="validarIdentificacion()" autofocus>

                            @if ($errors->has('identificacion'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('identificacion') }}</strong>
                                </span>
                            @endif
                            <span class="text-danger d-none" id="mensajeBanderaM">La identificacion no es válida</span>
                            <span class="text-danger d-none" id="mensajeTipoM">La identificacion para un vendedor debe
                                ser
                                RUC</span>
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name" class="col-md-4 control-label">{{ __('Name') }}</label>

                        <div class="col-md-12">
                            <input id="name" type="text" class="form-control" name="name"
                                value="{{ old('name') }}" autofocus>

                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    {!! apply_filters('ecommerce_customer_register_form_before', null) !!}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="col-md-4 control-label">{{ __('E-Mail Address') }}</label>

                        <div class="col-md-12">
                            <input id="email" type="email" class="form-control" name="email"
                                value="{{ old('email') }}">

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="col-md-4 control-label">{{ __('Password') }}</label>

                        <div class="col-md-12">
                            <input id="password" type="password" class="form-control" name="password">

                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <label for="password-confirm"
                            class="col-md-4 control-label">{{ __('Password confirmation') }}</label>

                        <div class="col-md-12">
                            <input id="password-confirm" type="password" class="form-control"
                                name="password_confirmation">

                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="hidden" name="agree_terms_and_policy" value="0">
                        <input class="form-control" type="checkbox" name="agree_terms_and_policy"
                            id="agree-terms-and-policy" value="1">
                        <label for="agree-terms-and-policy">{{ __('I agree to terms & Policy.') }}</label>

                        @if ($errors->has('agree_terms_and_policy'))
                            <span class="text-danger">{{ $errors->first('agree_terms_and_policy') }}</span>
                        @endif
                    </div>

                    @if (is_plugin_active('captcha') &&
                            setting('enable_captcha') &&
                            get_ecommerce_setting('enable_recaptcha_in_register_page', 0))
                        <div class="form-group mb-3">
                            {!! Captcha::display() !!}
                        </div>
                    @endif

                    <div class="form-group mb-3">
                        <div class="col-md-12 col-md-offset-4">
                            <button type="submit" class="submit btn btn-md btn-black">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </div>
                    <div class="text-center">
                        {!! apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, \Botble\Ecommerce\Models\Customer::class) !!}
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    var enviar = true;

    function validarIdentificacion() {
        var valor = document.getElementById('identificacion').value.trim();
        var cad = valor.trim();
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
                    $('#mensajeBanderaM').addClass("d-none");
                    $('#identificacion').removeClass("is-invalid");
                    enviar = true;

                } else {
                    $('#mensajeBanderaM').removeClass("d-none");
                    $('#identificacion').addClass("is-invalid");
                    camposvacios();
                    enviar = false;
                }
            } else {
                $('#mensajeBanderaM').removeClass("d-none");
                $('#identificacion').addClass("is-invalid");
                enviar = false;

            }
        } else
        if (longitud == 13 && cad !== "") {
            var extraer = cad.substr(10, 3);
            if (extraer == "001") {
                recuperarInformacion(cad);
                $('#mensajeBanderaM').addClass("d-none");
                $('#identificacion').removeClass("is-invalid");
                enviar = true;
            } else {

                $('#mensajeBanderaM').removeClass("d-none");
                $('#identificacion').addClass("is-invalid");
                enviar = false;
            }


        } else
        if (cad !== "") {

            $('#mensajeBanderaM').removeClass("d-none");
            $('#identificacion').addClass("is-invalid");
            camposvacios();
            enviar = false;
        }

    }

    function camposvacios() {

        $("#name").val("");
        $("#email").val("");
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
                    $("#name").val(data.razon_social);
                    $("#email").val(data.correo);

                }
            }
        });
    }




    document.getElementById("formularioM").addEventListener("submit", function(event) {
        if (enviar == false) {
            event.preventDefault();
        }
        var cad = document.getElementById('identificacion').value.trim();
        var longitud = cad.length;
        var tipo = document.querySelector('input[name="is_vendor"]:checked').value;


        if (longitud == 10 && tipo == 1) {
            event.preventDefault();
            $('#mensajeTipoM').removeClass("d-none");
            $('#identificacion').addClass("is-invalid");
        } else {
            $('#mensajeTipoM').addClass("d-none");
            $('#identificacion').removeClass("is-invalid");
        }



    });
</script>
