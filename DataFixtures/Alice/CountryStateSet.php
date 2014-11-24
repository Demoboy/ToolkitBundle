<?php

$set = new h4cc\AliceFixturesBundle\Fixtures\FixtureSet(array(
    'locale' => 'en_US',
    'seed' => 42,
    'do_drop' => true,
    'do_persist' => true,
    "order" => 99,
));

$set->addFile(__DIR__ . '/Fixtures/countries.yml', 'yaml');
$set->addFile(__DIR__ . '/Fixtures/states.yml', 'yaml');

return $set;