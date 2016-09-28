<?php

namespace Dywee\ContactBundle\Service;

use Symfony\Component\Routing\Router;

class AdminNavbarHandler
{

    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function getDashboardElement()
    {
        $elements = array(
            'key' => 'contact',
            'content' => array(
                'icon' => 'fa-enveloppe',
                'controller' => 'DyweeContactBundle:Admin:Navbar'
            )
        );

        return $elements;
    }
}