<a href="http://php.net/manual/en/functions.anonymous.php" > Anonymous functions  </a> <br />
$greet = function($name) <br/>
{
echo 'Hello '. $name . '&lt;br />';
};<br/>

$greet('World');<br/>
$greet('PHP');<br/>
<hr/>
<?php

$greet = function($name)
{
    echo 'Hello '. $name . '<br />';
};

$greet('World');
$greet('PHP');


