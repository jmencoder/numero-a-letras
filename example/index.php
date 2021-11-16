<?php
include __DIR__."/../vendor/autoload.php";
use jmencoder\NumerosALetras\NumerosALetras;

$converter = new NumerosALetras();

echo "toWords => ".$converter->toWords("200.50", 2, "Lempiras");
echo "<br>";
echo "toWords => ".$converter->toWords(1500, 2, "Lempiras");
echo "<br>";
echo "toWords => ".$converter->toWords(1500.581, 3, "Lempiras");
echo "<br>";
echo "toWords => ".$converter->toWords("200.50");
echo "<br>";
// $converter->suppress = true;
echo "toWords => ".$converter->toWords(101,0,"MESES");
echo "<br>";
echo "toInvoice => ".$converter->toInvoice("200.20", 2, "Lempiras");
echo "<br>";
echo "toInvoice => ".$converter->toInvoice(1200.50, 2, 'dolares');;
echo "<br>";
$converter->currencyPosition = 'after';
echo "toInvoice => ".$converter->toInvoice(1200.50, 2, 'dolares');;
echo "<br>";
exit;