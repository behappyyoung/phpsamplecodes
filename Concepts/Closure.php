<a href="http://php.net/manual/en/functions.anonymous.php" > Anonymous functions  </a> <br />
$greet = function($name) <br/>
{
echo 'Hello '. $name . '&lt;br />';
};<br/>

$greet('World');<br/>
$greet('PHP');<br/>
<hr/>
<a href="http://php.net/manual/en/closure.bind.php" > Bind to Object  </a>  to access private variable without changing original class<br />


class SimpleClass {<br/>
private $privateData = 2;<br/>
}<br/>

$simpleClosure = function() {<br/>
return $this->privateData;<br/>
};<br/>

$resultClosure = Closure::bind($simpleClosure, new SimpleClass(), 'SimpleClass');<br/>

echo $resultClosure();<br/>

<hr/>

Lazy loading..  <br />
use Monolog\Logger;  <br />
use Monolog\Handler\StreamHandler;  <br />

$logClosure = function() {  <br />
$log = new Logger('event');  <br />
$log->pushHandler(new StreamHandler("logfile.log", Logger::DEBUG));  <br />
return $log;  <br />
};  <br />
//logger will not be initialized until this point
$logger = $logClosure();  <br />
<br />
<a href="http://codesamplez.com/programming/php-closure-tutorial" > Reference  </a> <br />
<br />
<hr/>
<br />
<?php

$greet = function($name)
{
    echo 'Hello '. $name . '<br />';
};

$greet('World');
$greet('PHP');


class SimpleClass {
    private $privateData = 2;
}

$simpleClosure = function() {
    return $this->privateData;
};

$resultClosure = Closure::bind($simpleClosure, new SimpleClass(), 'SimpleClass');

echo $resultClosure();

