<?php

namespace Dywee\CoreBundle\Event;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\EventDispatcher\Event;

class AdminNavbarBuilderEvent extends Event
{
    protected $content;
    protected $user;

    public function __construct($content, UserInterface $user)
    {
        $this->content = $content;
        $this->user = $user;
    }

    public function getNavbar()
    {
        return $this->content;
    }

    public function addAdminElement($element)
    {
        $this->content[$element['key']] = $element['content'];
        return $this;
    }


    public function getUser()
    {
        return $this->user;
    }
}