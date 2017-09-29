Fixtures
----------------------------------

This bundle provides two different ways of loading data into the database.
These options are provided through Doctrine/FixturesBundle or h4cc/AliceFixturesBundle.
Use of h4cc/AliceFixturesBundle is preferered for extensablity purposes and speed.

Alice Fixtures
----------------------------------
Loading the doctrine fixtures is as easy as running 

    h4cc_alice_fixtures:load:sets

Doing this will load the database with all the countries, states (and relationships)
and will create a user based on the bundle's configuration options.

The sets will be loaded with the following priorities:
    CountrySet: 50
    UserSet: 100

Overriding User Creation Process
----------------------------------

The user and role set is designed to check for a yml file located in {appDir}/Resources/KMJToolKit/DataFixtures/Alice/Fixtures
if either a role.yml or a user.yml file is found that will be loaded instead of the default.

Doctrine Fixtures
----------------------------------
Loading the doctrine fixtures is as easy as running 

    app/console doctrine:fixtures:load

Doing this will load the database with all the countries, states (and relationships)
and will create a user based on the bundle's configuration options.

To allow overriding and customization the fixtures order is setup so that there would
be plenty of room to add any of your own fixtures before or after the loading of the fixtures
provided by this bundle. Those orders are:
    Countries: 10
    States: 11
    Roles: 90
    Users: 100

Overriding User Creation Process
----------------------------------
It might be nesisary to create an user with more properties than what is loaded in the fixtures
To do this it is recommended that you create a fixture that implements the ContainterAwareInterface, loads
the users you want (with the additional properies) and then calls

    $this->getContainer()->get("toolkit")->overrideFixtures(true);

When doctrine fixtures attempts to load the KMJUserFixture it will be skipped. To create a user
with the default properties and then add what needs to be added you can call

    $this->getContainer()->get("toolkit")->createAdminUser();

This will create a user (configured by FOSUserBundle) and return it with the data populated from the configs.

There is currently no way to prevent loading of the default roles, and no feature is planned. If you are interested in
further customization please use the AliceFixturesBundle
