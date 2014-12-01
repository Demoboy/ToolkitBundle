KMJToolkitBundle
================================
[ ![Codeship Status for Demoboy/ToolkitBundle](https://codeship.com/projects/07fa0450-5696-0132-90cb-0ea30a431f2b/status)](https://codeship.com/projects/49576)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/73c42571-63c2-455e-b6a5-ead8cefa10e1/mini.png)](https://insight.sensiolabs.com/projects/73c42571-63c2-455e-b6a5-ead8cefa10e1)

1) Installation
----------------------------------

KMJToolkitBundle can conveniently be installed via Composer. Just add the following to your composer.json file:

<pre>
// composer.json
{
    // ...
    require: {
        // ..
        "kmj/toolbundle": "~1.1@dev"
    }
}
</pre>


Then, you can install the new dependencies by running Composer's update command from the directory where your composer.json file is located:

<pre>
    php composer.phar update
</pre>

Now, Composer will automatically download all required files, and install them for you. All that is left to do is to update your AppKernel.php file, and register the new bundle:

<pre>
// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new KMJ\SyncBundle\KMJToolkitBundle(),
    // ...
);
</pre>

2) Configuration
----------------------------------

This bundle comes with a few configuration options.

<pre>
kmj_toolkit:
    administrator:
        firstname:      #Admin user's first name
        lastname:       #Admin user's last name
        username:       #Admin user's username
        email:          #Admin user's email address
        password:       #Admin user's password
</pre>


3) Features
----------------------------------

This bundle's goal is to provide quick setup of a Symfony2 project. These features include:

    * Quick load of countries and states (including relationships) into database through Doctrine fixtures or Alice fixtures (suggested)
    * Address entity that manages addresses
    * User class that uses roles as a relationship instead of FOSUser bundle storing them as an array
    * Address type for handling user submitted addresses
    * User type for registering users
    * Role Hierarchy
    * Quick setup of a super user in the db
    