<?php

/**
 * This file is part of the KMJToolkitBundle
 * @copyright (c) 2014, Kaelin Jacobson
 */
/**
 * This file runs the fixtures to load countries and states
 * into the database using Alice
 * 
 * @author Kaelin Jacobson <kaelinjacobson@gmail.com>
 */
$manager = $this->getContainer()->get('h4cc_alice_fixtures.manager');

$set = new \h4cc\AliceFixturesBundle\Fixtures\FixtureSet(array(
    'seed' => rand(),
    'do_drop' => true,
    'do_persist' => true,
    'order' => 50,
        )
);
$appDir = $this->getContainer()->get("kernel")->getRootDir();

$loadFile = function ($filename) use ($set, $appDir) {
    $bundlePath = "/Resources/KMJToolKit/DataFixtures/Alice/Fixtures";

    if (file_exists($appDir . $bundlePath . "/{$filename}.yml")) {
        $set->addFile($appDir . $bundlePath . "/{$filename}.yml", 'yaml');
    } else {
        $set->addFile(__DIR__ . "/Fixtures/{$filename}.yml", 'yaml');
    }
};


//look for files in app/Resources/KMJToolKit/DataFixtures/Alice/Fixtures/
$loadFile("countries");
$loadFile("states");

return $set;