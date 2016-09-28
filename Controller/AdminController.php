<?php

namespace Dywee\CoreBundle\Controller;

use Dywee\CoreBundle\DyweeCoreEvent;
use Dywee\CoreBundle\Event\AdminDashboardBuilderEvent;
use Dywee\CoreBundle\Event\AdminNavbarBuilderEvent;
use Dywee\CoreBundle\Event\AdminSidebarBuilderEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends Controller
{
    /**
     * @Route(name="dywee_admin_homepage", path="/admin")  @deprecated
     * @Route(name="admin_dashboard", path="/admin")
     */
    public function dashboardAction()
    {
        $event = new AdminDashboardBuilderEvent(array('boxes' => [], 'cards' => []), $this->getUser());

        $this->get('event_dispatcher')->dispatch(DyweeCoreEvent::BUILD_ADMIN_DASHBOARD, $event);

        return $this->render('DyweeCoreBundle:Admin:dashboard.html.twig', array(
            'dashboard' => $event->getDasboard(),
            'js' => $event->getJs()
        ));
    }

    public function navbarAction()
    {
        $event = new AdminNavbarBuilderEvent(array(), $this->getUser());

        $this->get('event_dispatcher')->dispatch(DyweeCoreEvent::BUILD_ADMIN_NAVBAR, $event);

        return $this->render('DyweeCoreBundle:Admin:navbar.html.twig', array('navbar' => $event->getNavbar()));
    }

    public function sidebarAction()
    {
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


        return $this->render('DyweeCoreBundle:Admin:sidebar.html.twig', array('sidebar' => $event->getSidebar()));
    }
}
