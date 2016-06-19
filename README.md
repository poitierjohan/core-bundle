# core-bundle

this README is currently in progress. Thank you for your understanding...

A simple bundle to handle other Dywee bundles. Add interesting functionnalities such as 
- ParentController to rule them all
- Js form collection type handling
- Data Preload for remote modal 
- The awesome Admin LTE template

##Installing

just run
```bash
$ composer require dywee/core-bundle
```

add the bundle to the kernel
```php
new Dywee\CoreBundle\DyweeCoreBundle(),
```

no more configuration needed

## Using the admin template

This bundle provides the famous Admin LTE template for you to easily design your symfony application.
Just extend the 'DyweeCoreBundle:Templates:admin.html.twig' template from your twig template and us ethe 'body' block tag.

```twig
{# Dywee\CMSBundle\Resources\views\admin.html.twig #}

{% extends "DyweeCoreBundle::admin.html.twig" %}

{% block title %}
    {{ parent() }}
{% endblock %}

{% block body %}
    My awesome body
{% endblock %}
```


### How to customize the navbar

### How to customize the left sidebar

## Using ParentController

## Using the modal preload

## Js form collection handling

the core bundle is providing 2 ways to handle collections in forms, based on jQuery.

The easiest way to handle a collection is using the dywee_handle_form_collection(collection_container_id) function (without any '#'):

```javascript
<script>
dywee_handle_form_collection('my_collection_id');
</script>
```
and it's all yu have to do.

For more flexibility, we add another method, to customize a little bit what is happening when you handle a form collection.

```javascript
<script>
dywee_handle_form_collection(collection_container_id, userConfig);
</script>
```

with

```javascript
var config = {
    container_type: 'div',
    label: 'Element',
    allow_add: true,
    allow_delete: true,
    add_btn: {
        'class': 'btn btn-default',
        icon: '',
        text: 'Ajouter un élément'
    },
    remove_btn: {
        'class': 'btn btn-default',
        icon: 'fa fa-trash',
        text: 'Supprimer'
    }
};
```
