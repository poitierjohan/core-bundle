<?php

namespace Dywee\CoreBundle\Model;

/**
 * Interface CustomerAwareInterface
 *
 * @package Dywee\CoreBundle\Model
 * @author Olivier Delbruyère
 */
interface CustomerInterface
{
    const UNKNOWN_GENDER = 'u';
    const GENDER_MALE = 'm';
    const GENDER_FEMALE =  'f';

    /**
     * @return int
     */
    public function getId();
}