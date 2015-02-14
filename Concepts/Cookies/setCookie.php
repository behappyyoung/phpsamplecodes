<?php

setcookie('test', 'testcookoe', time()+3600, '/');

?>

<a href="index.php"> show Cookie </a>
<br/>
<script>
    document.write(document.cookie);
</script>
<?php

echo '<br /><br /><br /> ========== source file ================= <br />';
echo highlight_file(__FILE__, true);