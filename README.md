# core-bundle

This Symfony bundle is required to ensure that any other dywee bundle works fine.

It brings to you some intersting resources to deploy a Symfony application. From the front end to the back end.

## 1. Installation

```bash
composer require dywee/core-bundle
```

## Enable the bundle

To start using the bundle, register the bundle in your application's kernel class:

```php
// app/AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Dywee\CoreBundle\DyweeCoreBundle(),
        // ...
    );
}
```
