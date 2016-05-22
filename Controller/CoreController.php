<?php

namespace Dywee\CoreBundle\Controller;

use libphonenumber\PhoneNumberUtil;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CoreController extends Controller
{
    public function fakeRouteAction()
    {
        return new Response('fake route');
    }


    public function sidebarAction()
    {
        $activeWebsite = $this->get('session')->get('activeWebsite');

        if($activeWebsite)
        {
            if($activeWebsite->getType() == 'commerce')
                return $this->render('DyweeCoreBundle:Sidebar:commerce.html.twig');
            else if($activeWebsite->getType() == 'music')
                return $this->render('DyweeCoreBundle:Sidebar:music.html.twig');
            else return $this->render('DyweeCoreBundle:Sidebar:main.html.twig');
        }
        else return $this->render('DyweeCoreBundle:Sidebar:main.html.twig');
    }

    public function indexAction()
    {
        return $this->redirect($this->generateUrl('dywee_cms_homepage'));
    }

    public function testMailAction()
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Confirmation de votre paiement')
            ->setFrom('info@dywee.com')
            ->setTo('olivier.delbruyere@hotmail.com')
            ->setBody('<p>Mail de test</p>');
        $message->setContentType("text/html");

        $this->get('mailer')->send($message);

        return new Response('mail envoyé');
    }

    public function robotsTxtAction()
    {
        return $this->render('DyweeCoreBundle::robots.txt.twig');

    }
}
