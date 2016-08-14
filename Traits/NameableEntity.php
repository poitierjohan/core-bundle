<?php

namespace Dywee\CoreBundle\Traits;

use Doctrine\ORM\Mapping as ORM;


trait NameableEntity
{
    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return NameableEntity
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }


}
