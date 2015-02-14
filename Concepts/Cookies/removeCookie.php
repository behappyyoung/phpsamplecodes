<?php

setcookie("test", "", time()-3600, '/');


?>

    <a href="index.php"> show Cookie </a>
    <br/>
    <script>
        console.log(document.cookie);
    </script>
<?php

echo '<br /><br /><br /> ========== source file ================= <br />';
echo highlight_file(__FILE__, true);