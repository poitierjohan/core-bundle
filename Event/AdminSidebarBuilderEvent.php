<?php

namespace Dywee\CoreBundle\Event;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\EventDispatcher\Event;

class AdminSidebarBuilderEvent extends Event
{
    protected $sidebar;
    protected $user;

    public function __construct($sidebar, UserInterface $user)
    {
        $this->sidebar = $sidebar;
        $this->user = $user;
    }

    public function getSidebar()
    {
        return $this->sidebar;
    }

    public function addAdminElement($element)
    {
        $this->sidebar['admin'][] = $element;
        return $this;
    }

    public function addSuperAdminElement($element)
    {
        $this->sidebar['superadmin'][] = $element;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }
}