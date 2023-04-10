<?php

namespace Botble\Ecommerce\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class ValidarCorreo implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $url = 'https://emailvalidation.abstractapi.com/v1/?api_key=fae435e4569b4c93ac34e0701100778c&email=' . $value;
        $correo = Http::withHeaders(['Content-Type' => 'application/json; charset=UTF-8', 'verify' => false,])
            ->withOptions(["verify" => false])
            ->get($url)
            ->json();
        if ($correo['deliverability'] != "DELIVERABLE") {
            if ($correo['is_valid_format']['value'] == true) {
                $url = 'https://api.debounce.io/v1/?email=' . rawurlencode($value) . '&api=6269b53f06aeb';
                $correo = Http::withHeaders(['Content-Type' => 'application/json; charset=UTF-8', 'verify' => false,])
                    ->withOptions(["verify" => false])
                    ->get($url)
                    ->json();
                if ($correo['debounce']['reason'] == "Deliverable" || $correo['debounce']['reason'] == "Deliverable, Role") {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Ingrese un Correo Válido';
    }
}
