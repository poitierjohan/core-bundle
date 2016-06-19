# core-bundle

this README is currently in progress. Thank you for your understanding...

A simple bundle to handle other Dywee bundles. Add interesting functionnalities such as 
- ParentController to rule them all
- Js form collection type handling
- Data Preload for remote modal 
- The awesome admin lte template

##Installing

just run the $ composer require dywee/core-bundle

add the bundle to the kernel
new Dywee\CoreBundle\DyweeCoreBundle(),

no more configuration needed

## Using the admin template

This bundle provides the famous Admin LTE template for you to easily design your symfony application.
Just extend the 'DyweeCoreBundle:Templates:admin.html.twig' template from your twig template and us ethe 'body' block tag.

{# Dywee\CMSBundle\Resources\views\admin.html.twig #}

{% extends "DyweeCoreBundle::admin.html.twig" %}

{% block title %}
    {{ parent() }}
{% endblock %}

{% block body %}
    My awesome body
{% endblock %}



### How to customize the navbar

### How to customize the left sidebar

## Using ParentController

## Using the modal preload

## Js form collection handling
