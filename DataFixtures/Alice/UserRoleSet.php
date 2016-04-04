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
if ($this->getContainer()->get("toolkit")->overrideFixture() == true) {
    return new h4cc\AliceFixturesBundle\Fixtures\FixtureSet();
}

$set = new \h4cc\AliceFixturesBundle\Fixtures\FixtureSet(array(
    'seed' => rand(),
    'do_drop' => false,
    'do_persist' => true,
    'order' => 100,
    )
);

$appDir = $this->getContainer()->get("kernel")->getRootDir();
$bundlePath = "/Resources/KMJToolKit/DataFixtures/Alice/Fixtures";

$loadFile = function ($filename) use ($set, $appDir, $bundlePath) {
    if (file_exists($appDir.$bundlePath."/{$filename}.yml")) {
        $set->addFile($appDir.$bundlePath."/{$filename}.yml", 'yaml');
    } else {
        $set->addFile(__DIR__."/Fixtures/{$filename}.yml", 'yaml');
    }
};

$loadFile("roles");

/**
 * This code will search for a user yml file. If the file is not found, then a file
 * will be created in the system temp dir with the data from KMJToolkitBundle configs
 * as YML. It will also determine the user class to use when importing the data.
 */
if (file_exists($appDir.$bundlePath."/users.yml")) {
    $set->addFile($appDir.$bundlePath."/users.yml", 'yaml');
} else {
    //create user from toolkit and create a yml to quickly load
    $tk = $this->getContainer()->get("toolkit");
    $user = $this->getContainer()->get('fos_user.user_manager')->createUser();

    $adminUser = $tk->createAdminUserArray();

    //create yml file
    $dumper = new Symfony\Component\Yaml\Dumper();

    $yml = $dumper->dump(
        array(
            get_class($user) => array(
                "super_user:" => array_merge_recursive(array("userRoles" => array(
                        "@superadmin")), $adminUser),
            )
        )
    );

    $cacheDir = $this->getContainer()->get("kernel")->getCacheDir();

    $tmpFile = fopen($cacheDir."/users.yml", "w");
    fwrite($tmpFile, $yml);
    $fileMeta = stream_get_meta_data($tmpFile);
    fclose($tmpFile);

    $set->addFile($fileMeta['uri'], "yaml");
}

return $set;
