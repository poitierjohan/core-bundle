<?php

namespace Dywee\CoreBundle\Controller;

use libphonenumber\PhoneNumberUtil;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    public function getSidebarAction()
    {
        $activeWebsite = $this->get('session')->get('activeWebsite');

        if($activeWebsite->getType() == 'commerce')
            return $this->render('DyweeCoreBundle:Sidebar:commerce.html.twig');
        else if($activeWebsite->getType() == 'music')
            return $this->render('DyweeCoreBundle:Sidebar:music.html.twig');
        else return $this->render('DyweeCoreBundle:Sidebar:main.html.twig');
    }
}
