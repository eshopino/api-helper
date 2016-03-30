<?php
$p = new Phar('eshopino.phar', 0, 'eshopino.phar');

$p->startBuffering();
$p->buildFromDirectory(__DIR__ . '/src/');

$p->setStub("<?php\nreturn require 'phar://' . __FILE__ . '/loader.php';\n__HALT_COMPILER();");
$p->stopBuffering();
