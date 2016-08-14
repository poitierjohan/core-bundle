<?php

namespace Dywee\CoreBundle\Traits;

use Doctrine\ORM\Mapping as ORM;


trait TimeDelimitableEntity
{
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $beginAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $endAt;

    /**
     * @return \DateTime
     */
    public function getBeginAt()
    {
        return $this->beginAt;
    }

    /**
     * @param \DateTime $beginAt
     * @return TimeDelimitableEntity
     */
    public function setBeginAt($beginAt)
    {
        $this->beginAt = $beginAt;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndAt()
    {
        return $this->endAt;
    }

    /**
     * @param \DateTime $endAt
     * @return TimeDelimitableEntity
     */
    public function setEndAt($endAt)
    {
        $this->endAt = $endAt;
        return $this;
    }


}
