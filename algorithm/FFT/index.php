<?php
/**
 * @author Michele Andreoli <michi.andreoli@gmail.com>
 * @name index.php
 * @version 0.1 updated 07-05-2010
 * @license http://opensource.org/licenses/gpl-license-php GNU Public License
 * @package FFT
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Fast Fourier Transform, FFT</title>
    </head>
    <body>
    		<?php 
    			require_once 'FFT.class.php';
    			
    			// Define a generic function
    			$f = array();
    			for ($i = 0; $i < 256; $i++) {
					if (($i >= 0 && $i <= 8) || ($i >= 248 && $i <= 255))
				    	$f[$i] = 1;
					else
				    	$f[$i] = 0;
				}
    			



            $string1 = file_get_contents('/var/www/test/string.txt');
            $array1 = explode(',', $string1);

            $size =  sizeof($array1);
echo $size;
            $fft = new FFT(2048);

            $fft_array = $fft->fft($array1);

            echo $fft->getDim();

            echo "<h1 style=\"font: bold 14px verdana;\">FFT: </h1>";
            for ($i = 0; $i < $fft->getDim(); $i++)
                echo "<p style=\"font: normal 10px verdana;\"><b>".$i.' : '. $array1[$i]."  => </b>  (".$fft_array[$i]->getReal().", ".$fft_array[$i]->getImag().")</p>";


            echo "<br/><h1 style=\"font: bold 14px verdana;\">FFT inverse:</h1>";
            for ($i = 0; $i < $fft->getDim(); $i++)
                echo "<p style=\"font: normal 10px verdana;\"><b>".$i.' : '. $array1[$i]."  => </b>  (".$fft_array[$i]->getReal().")</p>";


            /*
    			// Calculate the FFT of the function $f
    			$w = $fft->fft($f);
    			
    			echo "<h1 style=\"font: bold 14px verdana;\">FFT: </h1>";
    			for ($i = 0; $i < $fft->getDim(); $i++)
    				echo "<p style=\"font: normal 10px verdana;\"><b>".$i."</b>  (".$w[$i]->getReal().", ".$w[$i]->getImag().")</p>";
    			
    			// Calculate the inverse FFT of the function $w
    			$w = $fft->ifft($w);
    			
    			echo "<br/><h1 style=\"font: bold 14px verdana;\">FFT inverse:</h1>";
    			for ($i = 0; $i < $fft->getDim(); $i++)
    				echo "<p style=\"font: normal 10px verdana;\"><b>".$i."</b>  (".$w[$i]->getReal().")</p>";
*/
    		?>
    </body>
</html>