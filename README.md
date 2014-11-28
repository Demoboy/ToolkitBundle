KMJToolkitBundle
================================
[ ![Codeship Status for Demoboy/ToolkitBundle](https://codeship.com/projects/07fa0450-5696-0132-90cb-0ea30a431f2b/status)](https://codeship.com/projects/49576)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/f368f3d0-9d20-478d-ab00-ed96e9d1d976/mini.png)](https://insight.sensiolabs.com/projects/f368f3d0-9d20-478d-ab00-ed96e9d1d976)

1) Installation
----------------------------------

KMJToolkitBundle can conveniently be installed via Composer. Just add the following to your composer.json file:

<pre>
// composer.json
{
    // ...
    require: {
        // ..
        "kmj/toolbundle": "dev-master"
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
