<?php

namespace Dywee\CoreBundle\Controller;

use Dywee\CoreBundle\DyweeCoreEvent;
use Dywee\CoreBundle\Event\AdminDashboardBuilderEvent;
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
}
