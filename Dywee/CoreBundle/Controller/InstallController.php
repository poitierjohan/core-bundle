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
        /*$country = new Country();
        $country->setName('Belgique');
        $country->setIso('BE');
        $country->setPhonePrefix(32);
        $country->setState(1);
        $country->setVatRate(21);

        $em->persist($country);
        $em->flush();



        //Liste des réseaux sociaux pris en charge

        $socialNames = array('Facebook', 'Twitter', 'Google+', 'Instagram');

        foreach($socialNames as $socialName)
        {
            $social = new SocialItem();
            $social->setName($socialName);
            $em->persist($social);
        }
        $em->flush();*/

        $this->installCMS();

        return $this->redirect($this->generateUrl('dywee_cms_homepage'));
    }

    public function installCMS($callback = null)
    {
        $em = $this->getDoctrine()->getManager();

        $page = new Page();
        $page->setType(1);
        $page->setState(1);
        $page->setMenuName('Accueil');
        $page->setName('Accueil');
        $page->setActive(1);
        $page->setInMenu(1);
        $page->setMenuOrder(1);
        $page->setTemplate('index');

        $page->setContent('<p>Site en construction, restez à l\'écoute</p>');
        $page->setInMenu(true);
        $page->setActive(true);

        $website = $em->getRepository('DyweeWebsiteBundle:Website')->findOneById($this->container->getParameter('website.id'));

        $page->setWebsite($website);

        $em->persist($page);
        $em->flush();

        if($callback)
            return $this->forward($callback);
    }
}
