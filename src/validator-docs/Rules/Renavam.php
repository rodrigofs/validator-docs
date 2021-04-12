<?php

namespace geekcom\ValidatorDocs\Rules;

use function str_split;

class Renavam extends Sanitization
{
    public function validateRenavam($attribute, $renavam): bool
    {
        $renavam = $this->sanitize($renavam);

        $renavam = str_pad($renavam, 11, "0", STR_PAD_LEFT);

        if (!preg_match("/[0-9]{11}/", $renavam)) {
            return false;
        }

        $renavamSemDigito = substr($renavam, 0, 10);
        $renavamReversoSemDigito = strrev($renavamSemDigito);

        $soma = 0;
        $multiplicador = 2;
        for ($i = 0; $i < 10; $i++) {
            $algarismo = substr($renavamReversoSemDigito, $i, 1);
            $soma += $algarismo * $multiplicador;

            if ($multiplicador >= 9) {
                $multiplicador = 2;
            } else {
                $multiplicador++;
            }
        }

        $mod11 = $soma % 11;

        $ultimoDigitoCalculado = 11 - $mod11;

        $ultimoDigitoCalculado = ($ultimoDigitoCalculado >= 10 ? 0 : $ultimoDigitoCalculado);

        $digitoRealInformado = substr($renavam, -1);

        if ($ultimoDigitoCalculado == $digitoRealInformado) {
            return true;
        }

        return false;
    }
}
