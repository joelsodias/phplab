<?php

define("_BASE_YEAR",1900);
define("_ANO",365);
define("_DIA",1);
define("_HORA", _DIA/24);
define("_MINUTO", _HORA/60);
define("_SEGUNDO", _MINUTO/60);


/**
How to determine whether a year is a leap year
To determine whether a year is a leap year, follow these steps:

1. Se o ano é divisível por 4, siga proxima etapa, senão, não é bissexto
2. Se o ano é divisível por 100, siga proxima etapa, senão, o ano é bissexto
3. Se o ano é divisivel por 400, ele é bissexto, senão, o ano não é bissexto.

ref:
https://docs.microsoft.com/en-us/office/troubleshoot/excel/determine-a-leap-year
*/ 
function ano_bissexto($ano) {
    $result=false;
    if($ano % 4 === 0) {
      if($ano % 100 === 0) {
        if ($ano % 400 === 0) $result = true;       
      } else $result=true;      
    }
    return $result;
}

function ano_bissexto_fast($ano) {
    return (!($ano % 4) && (($ano % 100) || (!($ano % 100) && !($ano % 400))));
}

function bissexto_entre($ano1, $ano2) {
    $cont = 0;
    for ($a = $ano1; $a <= $ano2; $a++ ) {
        $cont += (ano_bissexto_fast($a)) ? 1 : 0;
    }
    return $cont;
}

function dias_mes($mes, $ano = 0) {

  if ($mes < 1 || $mes > 12) {
    throw new Exception('Erro: Mês inválido.');  
  }   

  return ($mes === 2) ? 28 + ((ano_bissexto_fast($ano) && ($ano > 0)) ? 1 : 0) : ((in_array($mes, array(1,3,5,7,8,10,12))) ? 31 : 30 );
          
}

function data_serial($ano,$mes,$dia) {
  $serial = ($ano - _BASE_YEAR) * _ANO + $dia;
 
  for ($i = 1; $i < $mes; $i++ ) {
     echo " dias_mes($i,$ano) = " . dias_mes($i,$ano) . "\n"; 
     $serial += dias_mes($i,$ano);  
  }
  return $serial + bissexto_entre(_BASE_YEAR, $ano);
}

function data($ano,$mes,$dia) {
    return array("_ano" => $ano, "_mes" => $mes, "_dia" => $dia, "_serial" => data_serial($ano,$mes,$dia));
}


function diff_datas($d1,$d2) {
  return $d2["_serial"] - $d1["_serial"];  
}


/*
echo "----- ano bissexto \n";
var_dump(ano_bissexto(1900));
var_dump(ano_bissexto(1991));
var_dump(ano_bissexto(1992));
var_dump(ano_bissexto(2000));
echo "-----  ano bissexto fast \n";
var_dump(ano_bissexto_fast(1900));
var_dump(ano_bissexto_fast(1991));
var_dump(ano_bissexto_fast(1992));
var_dump(ano_bissexto_fast(2000));
echo "-----  ano bissexto entre \n";
var_dump(bissexto_entre(1992,1996));
echo "-----  dias_mes \n";
var_dump(dias_mes(2));
var_dump(dias_mes(2,1996));
var_dump(dias_mes(2,1991));
*/

echo "-----  bissexto \n";
echo "2020";
var_dump(ano_bissexto(2020));
echo "-----  data serial \n";
var_dump(data_serial(2020,1,1));
var_dump(data_serial(2020,3,1));

echo "-----  diff datas \n";
$d1 = data(2020,1,1);
$d2 = data(2020,3,1);
var_dump(diff_datas($d1,$d2));


/*
$d1 = data(1900,1,1);
$d2 = data(1900,2,28);
var_dump(diff_datas($d1,$d2));

$d1 = data(1900,1,1);
$d2 = data(1900,3,1);
var_dump(diff_datas($d1,$d2));
*/
