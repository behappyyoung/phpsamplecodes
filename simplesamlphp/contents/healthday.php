<?php

define('FILE_ROOT', '/storage/data/healthday/');

$type = (isset($_GET['type']))? $_GET['type'] : 'video';


        $filedir = FILE_ROOT.$type;
        $filelists='';
        foreach (new DirectoryIterator($filedir) as $fileInfo) {
            if($fileInfo->isDot()) continue;
            $filelists .= $fileInfo->getFilename() . "/";
        }
        echo $filelists;



?>