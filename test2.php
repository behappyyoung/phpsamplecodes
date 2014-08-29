<?php


var_dump($_COOKIE);

setcookie("test", "", time()-3600);

var_dump($_COOKIE);

