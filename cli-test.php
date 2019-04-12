<?php

// Save The Keys In Your Configuration File
define('FIRSTKEY', 'nEZT13dW7Fzi68IV48KVIAv+4ECJcHwQE4HFG6Dz1ZE=');
define('SECONDKEY','ouj9xfTewG31YAk2lEkRkJTIYzxlhGXY9GaFGMAaL61QT5euOwWhAce+v92fGOLDIh+HhFHXLcXl5LKZbmgpfg==');

require_once(__DIR__.'/src/EncDec.php');

$filename_in  = $argv[1];
$filename_out = $argv[2];

\EvilKraft\encdec\EncDec::obfus($filename_in, $filename_out);