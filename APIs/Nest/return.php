<?php
/**
 * Created by Young Park.
 * Date: 2/11/15
 *
 */


foreach (getallheaders() as $name => $value) {
    echo "$name: $value\n";
}
echo '<br />';
echo $_SERVER['REQUEST_METHOD'];
$correct = true;
$subquery = '';


echo 'request : '. implode(',', $_REQUEST);
var_dump($_REQUEST);
echo 'post data : '.  file_get_contents('php://input');

