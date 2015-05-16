Installation
============

Prerequisites
-------------

### Less (recommended)

Less is not required, but is extremely helpful when using bootstrap variables, or mixins,
If you want to have a easier life, have a look into:

[Less Documentation](https://github.com/opwoco/BootstrapBundle/blob/master/Resources/doc/less-installation.md)

### Sass (recommended)

Sass is not required, but is extremely helpful when using bootstrap variables, or mixins,
If you want to have a easier life, have a look into:

[Sass Documentation](http://sass-lang.com/)

[Compass Documentation](http://compass-style.org/)


If you do not have less / Sass / Compass installed, currently you have several option, but please do NOT ask for help.

Installation
------------

1. Add this bundle to your project in composer.json:

    1.1. Plain BootstrapBundle

    ```json
    {
        "require": {
            "opwoco/bootstrap-bundle": "dev-master",
        }
    }
    ```
    1.2. BootstrapBundle and twitters bootstrap

    To have composer managing twitters bootstrap too, you can either run it with
    --install-suggests or add the following to your composer.json:

    ```json
    {
        "require": {
<<<<<<< HEAD
            "opwoco/bootstrap-bundle": "dev-master",
            "twbs/bootstrap": "dev-master"
=======
            "opwoco/bootstrap-bundle":    "dev-master",
            "twbs/bootstrap":           "dev-master"
>>>>>>> c6a228005309d9ed6d9b5f4d07665f30c5c33b33
        }
    }
    ```

    1.3. BootstrapBundle, twitters bootstrap and further suggests

    ```json
    {
        "require": {
<<<<<<< HEAD
            "opwoco/bootstrap-bundle": "dev-master",
            "twbs/bootstrap": "dev-master",
=======
            "opwoco/bootstrap-bundle":        "dev-master",
            "twbs/bootstrap":               "dev-master",
>>>>>>> c6a228005309d9ed6d9b5f4d07665f30c5c33b33
            "knplabs/knp-paginator-bundle": "dev-master",
            "knplabs/knp-menu-bundle":      "dev-master",
            "knplabs/knp-menu":             "2.0.*@dev",
            "craue/formflow-bundle":        "dev-master"
       }
    }
    ```

    1.4 Composer Scripts to Symlink Bootstrap Resources

    If you decided to let composer install twitters bootstrap, you might want to activate auto symlinking and checking, after composer update/install.
    So add this to your existing scripts section in your composer json:
    (recommended!)

    For using Less:

    ```json
    {
        "scripts": {
            "post-install-cmd": [
                "opwoco\\Bundle\\BootstrapBundle\\Composer\\ScriptHandler::postInstallSymlinkTwitterBootstrap"
            ],
            "post-update-cmd": [
                "opwoco\\Bundle\\BootstrapBundle\\Composer\\ScriptHandler::postInstallSymlinkTwitterBootstrap"
            ]
        }
    }
    ```

    For using Sass:

    ```json
    {
        "scripts": {
            "post-install-cmd": [
                "opwoco\\Bundle\\BootstrapBundle\\Composer\\ScriptHandler::postInstallSymlinkTwitterBootstrapSass"
            ],
            "post-update-cmd": [
                "opwoco\\Bundle\\BootstrapBundle\\Composer\\ScriptHandler::postInstallSymlinkTwitterBootstrapSass"
            ]
        }
    }
    ```

    There is also a console command to check and / or install this symlink:

    for less:

    ```bash
    php app/console opwoco:bootstrap:symlink:less
    ```

    for sass:

    ```bash
    php app/console opwoco:bootstrap:symlink:sass
    ```

    With these steps taken, bootstrap should be install into vendor/twbs/bootstrap/ and a symlink
    been created into vendor/opwoco/bootstrap-bundle/opwoco/Bundle/BootstrapBundle/Resources/public/bootstrap.


    1.5. Include bootstrap manually or in another way:

    For including bootstrap there are different solutions, why using this one?
<<<<<<< HEAD
    have a look into [Including Bootstrap](https://github.com/opwoco/BootstrapBundle/blob/master/Resources/doc/including-bootstrap.md)
    
=======
    have a look into [Including Bootstrap](https://github.com/phiamo/MopaBootstrapBundle/blob/master/Resources/doc/including-bootstrap.md)

>>>>>>> c6a228005309d9ed6d9b5f4d07665f30c5c33b33
    1.6 Sass Installation

    If you want to use Sass, check out the Documentation on Sass. Basically you just need to add one package to composer.json:

    ```json
       {
           "require": {
<<<<<<< HEAD
               "opwoco/bootstrap-bundle": "dev-master",
               "twbs/bootstrap-sass": "dev-master",
               "knplabs/knp-paginator-bundle": "dev-master",
               "knplabs/knp-menu-bundle": "dev-master",
               "craue/formflow-bundle": "~2.0"
=======
               "opwoco/bootstrap-bundle":         "dev-master",
               "twbs/bootstrap-sass":           "dev-master",
               "knplabs/knp-paginator-bundle":  "dev-master",
               "knplabs/knp-menu-bundle":       "dev-master",
               "craue/formflow-bundle":         "dev-master"
>>>>>>> c6a228005309d9ed6d9b5f4d07665f30c5c33b33
           }
       }
    ```
    You can also use the post-install cmd provided to setup the symlink for bootstrap-sass (cf. section 1.4)

2. Add this bundle to your app/AppKernel.php:

    ``` php
    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new opwoco\Bundle\BootstrapBundle\OpwocoBootstrapBundle(),
            // ...
        );
    }
    ```

    2.1. If you decided to add knp-menu-bundle, knp-paginator-bundle, or craue-formflow-bundle add them too:

    ``` php
    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new opwoco\Bundle\BootstrapBundle\OpwocoBootstrapBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Craue\FormFlowBundle\CraueFormFlowBundle(),
            // ...
        );
    }
    ```

3. To activate certain feature sets you need to add to your config:

    ``` yaml
    opwoco_bootstrap:
        form: ~  # Adds twig form theme  support
        menu: ~  # enables twig helpers for menu
    ```

4. If you like further tweak your config.yml (not mandatory)

    ``` yaml
    opwoco_bootstrap:
        form:
            show_legend: false          # default is true
            show_child_legend: false    # default is true
            error_type: block           # or inline which is default
        menu:
            template: MyBundles:Menu:template.html.twig
    ```

5. Setup Assetic Assets

    We have tried to make this as flexible as possible without have lots of different
    versions / tags on github so we have created different configurations for your
    projects:

    - Resources/config/assetic/bootstrap_less.yml
    - Resources/config/assetic/bootstrap_sass.yml
    - Resources/config/assetic/bootstrap_3.2_sass.yml

    Depending on which one you use, this will setup your bootstrap assets in an easy
    to use shortcut for assetic:

    ```jinja
    {% stylesheets '@bootstrap_css' %}
    <link rel="stylesheet" href="{{ asset_url }}">
    {% endstylesheets %}
    ```

    The same goes for `@bootstrap_js` as well. You can choose to use these or not,
    or create your own. Just look at the contents of those files and make your own.
    This method allows us to have one main base template with different assets.

    You can also add more assets to assetic as well:

    ```jinja
    {% stylesheets '@bootstrap_css' '/bundles/acme/site.css' %}
    <link rel="stylesheet" href="{{ asset_url }}">
    {% endstylesheets %}
    ```

    Or copy and paste the config file you're using and make the edits there.

---

[Using bootstrap in the layout](https://github.com/opwoco/BootstrapBundle/blob/master/Resources/doc/2-base-templates.md) >>
