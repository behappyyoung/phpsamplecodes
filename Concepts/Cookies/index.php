<?php


echo 'Current Cookies <pre>';
print_r($_COOKIE);
echo '</pre>';
?>
<style>
    a {text-decoration: none;}
</style>

    <a href="setCookie.php"> set Cookie "test" </a>
    <br />
    <br />
    <a href="removeCookie.php"> remove Cookie "test" </a>

<?php
echo '<br /><br /><br /> ========== source file ================= <br />';
echo highlight_file(__FILE__, true);