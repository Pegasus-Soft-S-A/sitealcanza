<?php

namespace Botble\Ecommerce\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class ValidarIdentificacion implements Rule
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
        $valor = trim($value);
        $cad = trim($value);
        $total = 0;
        $longitud = strlen($cad);
        $longcheck = $longitud - 1;
        $digitos = array_map('intval', str_split($cad));
        $codigo_provincia = $digitos[0] * 10 + $digitos[1];

        if ($cad !== "" && $longitud === 10) {
            if ($cad != '2222222222' && $codigo_provincia >= 1 && ($codigo_provincia <= 24 || $codigo_provincia == 30)) {
                for ($i = 0; $i < $longcheck; $i++) {
                    if ($i % 2 === 0) {
                        $aux = $cad[$i] * 2;
                        if ($aux > 9) $aux -= 9;
                        $total += $aux;
                    } else {
                        $total += intval($cad[$i]);
                    }
                }
                $total = $total % 10 ? 10 - $total % 10 : 0;

                if ($cad[$longitud - 1] == $total) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else if ($longitud == 13 && $cad !== "") {
            $extraer = substr($cad, 10, 3);
            if ($extraer == "001") {
                return true;
            } else {
                return false;
            }
        } else if ($cad !== "") {
            return false;
        } else {
            return false;
        }
    }

   

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El número de identificación no es válido';
    }
}
