<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidarCedulaEcuatoriana implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->validarCedulaEcuatoriana($value)) {
            $fail('La cédula ingresada no es válida para Ecuador.');
        }
    }

    private function validarCedulaEcuatoriana($cedula)
    {
        // Verificar que tenga 10 dígitos
        if (strlen($cedula) != 10 || !ctype_digit($cedula)) {
            return false;
        }

        // Los dos primeros dígitos deben corresponder a una provincia válida (01-24)
        $provincia = intval(substr($cedula, 0, 2));
        if ($provincia < 1 || $provincia > 24) {
            return false;
        }

        // El tercer dígito debe ser menor a 6 (para cédulas de personas naturales)
        $tercerDigito = intval($cedula[2]);
        if ($tercerDigito >= 6) {
            return false;
        }

        // Algoritmo de validación del dígito verificador
        $coeficientes = [2, 1, 2, 1, 2, 1, 2, 1, 2];
        $suma = 0;

        for ($i = 0; $i < 9; $i++) {
            $producto = intval($cedula[$i]) * $coeficientes[$i];
            if ($producto >= 10) {
                $producto = $producto - 9;
            }
            $suma += $producto;
        }

        $digitoVerificador = intval($cedula[9]);
        $verificacion = (10 - ($suma % 10)) % 10;

        return $verificacion == $digitoVerificador;
    }
}
