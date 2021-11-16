## Números en Letras PHP

Librería PHP para convertir un número en letras.

## Requerimientos

PHP `7.2` o superior.

## Instalar

Instalar usando Composer

```bash
composer require jmencoder/numero-a-letras
```

## Uso

Agregar referencia a librería.

```php

require 'vendor/autoload.php';
use jmencoder\NumerosALetras\NumerosALetras;
```

### Convertir un número en letras

```php
$converter = new NumerosALetras();
echo $converter->toWords($number, $decimals,$currency);
```

Parámetros:

- int|float `$number` (requerido) El número a convertir.

- int `$decimals` (opcional) Establece la cantidad de decimales, valor por defecto se establece 2.

- string `$text` (opcional) Establece el texto que se presentara concatenado al valor por defecto se establece vacia.

### Convertir un número a letras en formato de facturación fiscal financiera

```php
$converter = new NumerosALetras();
echo $converter->toInvoice($number, $decimals, $currency);
```

Parámetros:

- int|float `$number` (requerido) El número a convertir.

- int `$decimals` (opcional) Establece la cantidad de decimales, valor por defecto se establece 2.

- string `$currency` (opcional) Establece el texto que se presentara como moneda por defecto se establece vacia.

### Suprimir el uno

Para cambiar la palabra 'UNO' por 'UN' hacer lo siguiente:

```php
$converter = new NumerosALetras();
$converter->suppress = true;
```

### Concatenado

Para cambiar la palabra 'CON' por otra según sea necesario hacer lo siguiente:

```php
$converter = new NumerosALetras();
$converter->anexar = 'Y';
```

## Ejemplos de uso

```php
$converter = new NumerosALetras();
echo $converter->toWords(1500);

//MIL QUINIENTOS
```

```php
$converter = new NumerosALetras();
echo $converter->toWords(101,0,"MESES");

//CIENTO UNO MESES
```

```php
$converter = new NumerosALetras();
$converter->suppress = true;
echo $converter->toWords(101,0,"MESES");

//CIENTO UN MESES
```

```php
$converter = new NumerosALetras();
echo $converter->toInvoice(1200.50, 2, 'dolares');

//MIL DOSCIENTOS DOLARES CON 50/100
```

```php
$converter = new NumerosALetras();
$converter->currencyPosition = 'after';
echo $converter->toInvoice(1200.50, 2, 'dolares');

//MIL DOSCIENTOS CON 50/100 DOLARES
```

## Licencia

Software de código abierto con licencia [MIT license](LICENSE).
