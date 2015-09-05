<?php

namespace Dywee\CoreBundle\Controller;

use Dywee\AddressBundle\Entity\Country;
use Dywee\CMSBundle\Entity\Page;
use Dywee\ProductBundle\Entity\Image;
use Dywee\SocialBundle\Entity\SocialItem;
use Dywee\WebsiteBundle\Entity\Website;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class InstallController extends Controller
{
    public function installAction()
    {
        $em = $this->getDoctrine()->getManager();
        //Liste des pays
        $country = new Country();
        $country->setName('Belgique');
        $country->setIso('BE');
        $country->setPhonePrefix(32);
        $country->setState(1);
        $country->setVatRate(21);

        $em->persist($country);
        $em->flush();

        $page = new Page();
        $page->setType(1);
        $page->setState(1);
        $page->setMenuName('Accueil');
        $page->setName('Accueil');
        $page->setActive(1);
        $page->setInMenu(1);
        $page->setMenuOrder(1);
        $page->setContent('Votre page d\'acceuil');
        $page->setTemplate('index');

        $website = new Website();
        $website->setName('Nouveau site');

        $page->setWebsite($website);

        $em->persist($page);
        $em->flush();

        //Liste des réseaux sociaux pris en charge

        $socialNames = array('Facebook', 'Twitter', 'Google+', 'Instagram');

        foreach($socialNames as $socialName)
        {
            $social = new SocialItem();
            $social->setName($socialName);
            $em->persist($social);
        }
        $em->flush();

        return $this->redirect($this->generateUrl('dywee_cms_homepage'));
    }
}
