<?php

namespace jmencoder\NumerosALetras;

class NumerosALetras
{
    /**
     * @var array
     */
    private $unidades = [
        '',
        'UNO ',
        'DOS ',
        'TRES ',
        'CUATRO ',
        'CINCO ',
        'SEIS ',
        'SIETE ',
        'OCHO ',
        'NUEVE ',
        'DIEZ ',
        'ONCE ',
        'DOCE ',
        'TRECE ',
        'CATORCE ',
        'QUINCE ',
        'DIECISÉIS ',
        'DIECISIETE ',
        'DIECIOCHO ',
        'DIECINUEVE ',
        'VEINTE ',
    ];

    /**
     * @var array
     */
    private $decenas = [
        'VEINTI',
        'TREINTA ',
        'CUARENTA ',
        'CINCUENTA ',
        'SESENTA ',
        'SETENTA ',
        'OCHENTA ',
        'NOVENTA ',
        'CIEN ',
    ];

    /**
     * @var array
     */
    private $centenas = [
        'CIENTO ',
        'DOSCIENTOS ',
        'TRESCIENTOS ',
        'CUATROCIENTOS ',
        'QUINIENTOS ',
        'SEISCIENTOS ',
        'SETECIENTOS ',
        'OCHOCIENTOS ',
        'NOVECIENTOS ',
    ];

    /**
     * @var array
     */
    private $acentosExcepciones = [
        'VEINTIDOS'  => 'VEINTIDÓS ',
        'VEINTITRES' => 'VEINTITRÉS ',
        'VEINTISEIS' => 'VEINTISÉIS ',
    ];

    /**
     * @var string
     */
    public $anexar = 'CON';

    /**
     * Conversión de la palabra uno a un.
     *
     * @var bool
     */
    public $suppress = false;

    /**
     * Posición del texto de la moneda permite (before = antes de los centavos) tambien (after = despues de los centavos).
     *
     * @var bool
     */
    public $currencyPosition = 'before';

    /**
     * Formatea y convierte un número a letras.
     *
     * @param int|float $number
     * @param int       $decimals
     *
     * @return string
     */
    public function ToWords($number, $decimals = 2, $text = '')
    {
        $this->checksuppress();

        if (empty($number))
        {
            trigger_error("toInvoice() expects parameter 1 to be number, empty given", E_USER_WARNING);
            return;
        }

        if (!empty($text))
        {
            return $this->toString($number, $decimals, $text);
        }

        $number = number_format($number, $decimals, '.', '');

        $splitNumber = explode('.', $number);

        $splitNumber[0] = $this->checkNumber($splitNumber[0]);

        if (!empty($splitNumber[1])) {
            $splitNumber[1] = $this->transformNumber($splitNumber[1]);
        }

        return $this->concatenar($splitNumber);
    }

    /**
     * Formatea y convierte un número a letras en formato moneda.
     *
     * @param int|float $number
     * @param int       $decimals
     * @param string    $currency
     * @param string    $cents
     *
     * @return string
     */
    private function getMoney($number, $decimals = 2, $currency = '', $cents = '')
    {
        $this->checksuppress();

        if (empty($number))
        {
            trigger_error("getMoney() expects parameter 1 to be number, empty given", E_USER_WARNING);
            return;
        }

        $number = number_format($number, $decimals, '.', '');

        $splitNumber = explode('.', $number);

        $splitNumber[0] = $this->checkNumber($splitNumber[0]) . ' ' . mb_strtoupper($currency, 'UTF-8');

        if (!empty($splitNumber[1])) {
            $splitNumber[1] = $this->transformNumber($splitNumber[1]);
        }

        if (!empty($splitNumber[1])) {
            $splitNumber[1] .= ' ' . mb_strtoupper($cents, 'UTF-8');
        }

        return $this->concatenar($splitNumber);
    }

    /**
     * Formatea y convierte un número a letras en formato libre.
     *
     * @param int|float $number
     * @param int       $decimals
     * @param string    $whole_str
     * @param string    $decimal_str
     *
     * @return string
     */
    private function toString($number, $decimals = 2, $whole_str = '', $decimal_str = '')
    {
        if (empty($number))
        {
            trigger_error("toString() expects parameter 1 to be number, empty given", E_USER_WARNING);
            return;
        }
        return $this->getMoney($number, $decimals, $whole_str, $decimal_str);
    }

    /**
     * Formatea el valor a letras en formato facturación digital.
     *
     * @param int|float $number
     * @param int       $decimals
     * @param string    $currency
     *
     * @return string
     */
    public function toInvoice($number, $decimals = 2, $currency = '')
    {
        $this->checksuppress();
        
        if (empty($number))
        {
            trigger_error("toBank() expects parameter 1 to be number, empty given", E_USER_WARNING);
            return;
        }

        $number = number_format($number, $decimals, '.', '');

        $splitNumber = explode('.', $number);

        $splitNumber[0] = $this->checkNumber($splitNumber[0]);

        if (!empty($splitNumber[1])) {
            $splitNumber[1] .= '/100 ';
        } else {
            $splitNumber[1] = '00/100 ';
        }

        if ($this->currencyPosition==='before')
        {
            $splitNumber[0] = $splitNumber[0]." ".mb_strtoupper($currency, 'UTF-8');
            // $splitNumber = array_merge(array_slice($splitNumber, 0, 1), [mb_strtoupper($currency, 'UTF-8')], array_slice($splitNumber, 1));
        }
        else
        {
            // $splitNumber[2] = mb_strtoupper($currency, 'UTF-8');
            $splitNumber[1] = $splitNumber[1]." ".mb_strtoupper($currency, 'UTF-8');
        }

        return $this->concatenar($splitNumber);
    }

    /**
     * Valida si debe aplicarse supresión del uno.
     *
     * @return void
     */
    private function checksuppress()
    {
        if ($this->suppress === true) {
            $this->unidades[1] = 'UN ';
        }
    }

    /**
     * Formatea el entero del número.
     *
     * @param string $number
     *
     * @return string
     */
    private function checkNumber($number)
    {
        if ($number == '0') {
            $number = 'CERO ';
        } else {
            $number = $this->transformNumber($number);
        }

        return $number;
    }

    /**
     * Concatena las partes formateadas.
     *
     * @param array $splitNumber
     *
     * @return string
     */
    private function concatenar($splitNumber)
    {
        return implode(' ' . mb_strtoupper($this->anexar, 'UTF-8') . ' ', array_filter($splitNumber));
    }

    /**
     * Convierte número a letras.
     *
     * @param string $number
     *
     * @return string
     */
    private function transformNumber($number)
    {
        $converted = '';

        if (($number < 0) || ($number > 999999999)) {
            trigger_error("The number must be > 0 and < 999999999", E_USER_WARNING);
            return;
        }

        $numberStrFill = str_pad($number, 9, '0', STR_PAD_LEFT);
        $millones = substr($numberStrFill, 0, 3);
        $miles = substr($numberStrFill, 3, 3);
        $cientos = substr($numberStrFill, 6);

        if (intval($millones) > 0) {
            if ($millones == '001') {
                $converted .= 'UN MILLON ';
            } elseif (intval($millones) > 0) {
                $converted .= sprintf('%sMILLONES ', $this->convertGroup($millones));
            }
        }

        if (intval($miles) > 0) {
            if ($miles == '001') {
                $converted .= 'MIL ';
            } elseif (intval($miles) > 0) {
                $converted .= sprintf('%sMIL ', $this->convertGroup($miles));
            }
        }

        if (intval($cientos) > 0) {
            if ($cientos == '001') {
                $this->suppress === true ? $converted .= 'UN ' : $converted .= 'UNO ';
            } elseif (intval($cientos) > 0) {
                $converted .= sprintf('%s ', $this->convertGroup($cientos));
            }
        }

        return trim($converted);
    }

    /**
     * @param string $n
     *
     * @return string
     */
    private function convertGroup($n)
    {
        $output = '';

        if ($n == '100') {
            $output = 'CIEN ';
        } elseif ($n[0] !== '0') {
            $output = $this->centenas[$n[0] - 1];
        }

        $k = intval(substr($n, 1));

        if ($k <= 20) {
            $unidades = $this->unidades[$k];
        } else {
            if (($k > 30) && ($n[2] !== '0')) {
                $unidades = sprintf('%sY %s', $this->decenas[intval($n[1]) - 2], $this->unidades[intval($n[2])]);
            } else {
                $unidades = sprintf('%s%s', $this->decenas[intval($n[1]) - 2], $this->unidades[intval($n[2])]);
            }
        }

        $output .= array_key_exists(trim($unidades), $this->acentosExcepciones) ?
            $this->acentosExcepciones[trim($unidades)] : $unidades;

        return $output;
    }
}
