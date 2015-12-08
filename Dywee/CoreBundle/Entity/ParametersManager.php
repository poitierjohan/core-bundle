<?php

namespace Dywee\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ParameterManager
 *
 * @ORM\Table("websites_settings")
 * @ORM\Entity(repositoryClass="Dywee\CoreBundle\Entity\ParametersManagerRepository")
 */
class ParametersManager
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="Dywee\WebsiteBundle\Entity\Website")
     */
    private $website;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ParametersManager
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set value
     *
     * @param string $value
     * @return ParametersManager
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set website
     *
     * @param \Dywee\WebsiteBundle\Entity\Website $website
     * @return ParametersManager
     */
    public function setWebsite(\Dywee\WebsiteBundle\Entity\Website $website = null)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return \Dywee\WebsiteBundle\Entity\Website
     */
    public function getWebsite()
    {
        return $this->website;
    }
}
