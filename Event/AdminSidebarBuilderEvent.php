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
        if (!is_array($element)) {
            return $this;
        }

        if (!array_key_exists('key', $element)) {
            foreach ($element as $subElement) {
                $this->addAdminElement($subElement);
            }
        } elseif (array_key_exists($element['key'], $this->sidebar['admin'])) {
            if(!array_key_exists('children', $element)) {
                throw new \Exception('no children found for key ' . $element['key']);
            }
            $this->sidebar['admin'][$element['key']]['children'] = array_merge($this->sidebar['admin'][$element['key']]['children'], $element['children']);
        } else {
            $this->sidebar['admin'][$element['key']] = $element;
        }

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