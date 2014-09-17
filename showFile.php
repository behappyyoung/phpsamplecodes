<?php
echo 'END<br /><br /><br /> ========== source file =================';
echo substr(highlight_file(__FILE__, true), 0 , strpos(highlight_file(__FILE__, true), 'END')-47);
echo '?><br /><br /><hr /><br />';
?>