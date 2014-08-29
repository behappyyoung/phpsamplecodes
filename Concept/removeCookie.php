<?php


echo 'Before <pre>';
print_r($_COOKIE);
echo '</pre>';


setcookie("test", "", time()-3600);

?>
setcookie("test", "", time()-3600); <br />
    need refresh <br />
<?php

echo 'After <pre>';
print_r($_COOKIE);
echo '</pre>';


