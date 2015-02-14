<?php
// use XOR

$a = '3';
$b = '4';


echo $a.'--'.$b.'<br />';

$a =  ($a) ^ ($b);
$b = ($b) ^ $a;
$a = $a ^ $b;

echo $a.'--'. $b.'<br />';



$a = 'a';
$b = 'b';

echo $a.'--'.$b.'<br />';
echo ord($a).'--'.ord($b).'<br />';

$a =  ord($a) ^ ord($b);
$b = ord($b) ^ $a;
$a = $a ^ $b;

echo chr($a).'--'. chr($b).'<br />';

echo '<br /><br /><br /> ========== source file ================= <br />';
echo highlight_file(__FILE__, true);