KMJToolkitBundle
================================
[ ![Codeship Status for Demoboy/ToolkitBundle](https://codeship.com/projects/07fa0450-5696-0132-90cb-0ea30a431f2b/status)](https://codeship.com/projects/49576)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/73c42571-63c2-455e-b6a5-ead8cefa10e1/mini.png)](https://insight.sensiolabs.com/projects/73c42571-63c2-455e-b6a5-ead8cefa10e1)
[![Dependency Status](https://www.versioneye.com/user/projects/5491c3ecdd709d6dbd000203#tab-licenses/badge.svg?style=flat)](https://www.versioneye.com/user/projects/5491c3ecdd709d6dbd000203#tab-licenses)
[![Coverage Status](https://coveralls.io/repos/Demoboy/ToolkitBundle/badge.svg?branch=1.1)](https://coveralls.io/r/Demoboy/ToolkitBundle?branch=1.1)
[![Latest Stable Version](https://poser.pugx.org/kmj/toolkitbundle/v/stable.svg)](https://packagist.org/packages/kmj/toolkitbundle)
[![Total Downloads](https://poser.pugx.org/kmj/toolkitbundle/downloads.svg)](https://packagist.org/packages/kmj/toolkitbundle)

1) Installation
----------------------------------

KMJToolkitBundle can conveniently be installed via Composer. Just run the following command from your project directory

composer require "kmj/toolkitbundle":1.1.*

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
    rootdir:            #The root directory of the application defaults to %kernel.root_dir%
    enckey:             #The encryption key to use to encrypt documents
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
    * Document entities for handling user submitted documents


4) Documents
----------------------------------

This bundle also provides Entity to handle documents. Instances of EncryptedDocument and 
HiddenDocument are all saved to the app/Resources/protectedDocuments folder and instances of WebDocument are 
saved to web/uploads/documents Based on the entity it will also encrypt documents as it is written to
the file system. The files are automatically saved to file system during when the 
entity is persisted to the database using lifecycle callbacks. To view or download these files a controller has been provided.
To provide a link to download a document use the following twig snippet:

{{ path("kmj_toolkit_document_download(encrypted|hidden)", document: document.id) }}

And to include an image or view inline you can use:

{{ path("kmj_toolkit_document_view(encrypted|hidden)", document: document.id) }}

There are no helper methods for instances of WebDocument as they are accessible through traditional HTTP requests
