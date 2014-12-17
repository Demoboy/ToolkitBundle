KMJToolkitBundle
================================

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
