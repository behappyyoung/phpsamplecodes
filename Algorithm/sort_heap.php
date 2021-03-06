<?php
function build_heap(&$array, $i, $t){
    $tmp_var = $array[$i];
    $j = $i * 2 + 1;

    while ($j <= $t)  {
        if($j < $t)
            if($array[$j] < $array[$j + 1]) {
                $j = $j + 1;
            }
        if($tmp_var < $array[$j]) {
            $array[$i] = $array[$j];
            $i = $j;
            $j = 2 * $i + 1;
        } else {
            $j = $t + 1;
        }
    }
    $array[$i] = $tmp_var;
}

function heap_sort($array) {
    //This will heapify the array
    $init = (int)floor((count($array) - 1) / 2);
    for($i=$init; $i >= 0; $i--){
        $count = count($array) - 1;
        build_heap($array, $i, $count);
    }

    //swaping of nodes
    for ($i = (count($array) - 1); $i >= 1; $i--)  {
        $tmp_var = $array[0];
        $array [0] = $array [$i];
        $array [$i] = $tmp_var;
        build_heap($array, 0, $i - 1);
    }
    return $array;
}

// Demo
$array = array(9,8,7,6,5,4,3,2,1,0,10,1000,0);
$rearray = heap_sort($array);
print_r($rearray);

echo '<br /><br /><br /> ========== source file ================= <br />';
echo highlight_file(__FILE__, true);

?>

