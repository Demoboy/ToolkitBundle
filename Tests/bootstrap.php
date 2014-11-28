<?php
/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
if (!is_file($autoloadFile = __DIR__ . '/../../../../../autoload.php')) {
    throw new \LogicException('Could not find autoload.php in vendor/. Did you run "composer install --dev"?');
}

require $autoloadFile;
