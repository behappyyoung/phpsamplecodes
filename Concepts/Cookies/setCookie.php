<?php

setcookie('test', 'testcookoe', time()+3600, '/');

?>

<a href="index.php"> show Cookie </a>

<?php

echo '<br /><br /><br /> ========== source file ================= <br />';
echo highlight_file(__FILE__, true);