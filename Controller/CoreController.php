<?php

namespace Dywee\CoreBundle\Controller;

use libphonenumber\PhoneNumberUtil;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Dywee\CoreBundle\Event\AdminSidebarBuilderEvent;
use Dywee\CoreBundle\DyweeCoreEvent;
use Symfony\Component\EventDispatcher\Event;

class CoreController extends Controller
{
    public function fakeRouteAction()
    {
        return new Response('fake route');
    }


    public function sidebarAction()
    {
    	
	    /*if($activeWebsite->getType() == 'commerce')
	        return $this->render('DyweeCoreBundle:Sidebar:commerce.html.twig');
	    else if($activeWebsite->getType() == 'music')
	        return $this->render('DyweeCoreBundle:Sidebar:music.html.twig');
	    else return $this->render('DyweeCoreBundle:Sidebar:main.html.twig'); */
	    $sidebar = array(
	    	'admin' => array(
	    		array(
	    			'type' => 'header',
	    			'label' => 'main navigation'
	    			),
	    		array(
	    			'icon' => 'fa fa-home',
	    			'label' => 'Accueil',
	    			'route' => $this->generateUrl('admin_dashboard')
	    			),
	    		),
	    	'superAdmin' => array(
	    		array(
	    			'type' => 'header',
	    			'label' => 'super admin'
	    			)
	    		)
	    	);

	    $event = new AdminSidebarBuilderEvent($sidebar, $this->getUser());

	    $this->get('event_dispatcher')->dispatch(DyweeCoreEvent::BUILD_ADMIN_SIDEBAR, $event);


	    return $this->render('DyweeCoreBundle:Sidebar:sidebar.html.twig', array('sidebar' => $event->getSidebar()));
    }

    public function indexAction()
    {
        return $this->redirect($this->generateUrl('cms_homepage'));
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
