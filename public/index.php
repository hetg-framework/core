<?php

include __DIR__. '/../autoload.php';

$app = new \Hetg\LunchGenerator\LunchGenerator();
echo $app::start();