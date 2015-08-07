<?php

namespace Dywee\CoreBundle\Controller;

use Dywee\BlogBundle\Entity\Article;
use Dywee\CMSBundle\Entity\Page;
use Dywee\EshopBundle\Entity\Address;
use Dywee\EshopBundle\Entity\Country;
use Dywee\OrderBundle\Entity\BaseOrder;
use Dywee\OrderBundle\Entity\OrderElement;
use Dywee\ProductBundle\Entity\Category;
use Dywee\ProductBundle\Entity\Image;
use Dywee\ProductBundle\Entity\Product;
use Dywee\ShipmentBundle\Entity\Deliver;
use libphonenumber\PhoneNumberUtil;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class UpdateController extends Controller
{
    public function updateAction()
    {
        $em = $this->getDoctrine()->getManager();
        $response = '';

        //Mise à jour des commandes foireuses niveau tva

        $cr = $em->getRepository('DyweeOrderBundle:BaseOrder');
        $response .= '<p>Rectification de la tva</p>';

        /*BE 127
        BE 133 à BE 137
        FR 50 à FR 0057
        */

        $facturesACorrigerRef = array('BE 0127' , 'BE 0133', 'BE 0134', 'BE 0135', 'BE 0136', 'BE 0137',
            'FR 0050', 'FR ', 'FR ', 'FR ', 'FR ', 'FR ', 'FR ', 'FR ');

        $facturesACorriger = $cr->findByInvoiceReference('BE 0127');

        foreach($facturesACorriger as $order)
        {
            $response .= '<p>Préparation de la rectification pour la commande #'.$order->getId().'</p>';
            $order->setDeposit(1);
            $em->persist($order);
        }
        $em->flush();

        foreach($facturesACorriger as $order)
        {
            $response .= '<p>Rectification pour la commande #'.$order->getId().'</p>';
            $order->setDeposit(0);
            $em->persist($order);
        }
        $em->flush();

        //Affichage de la réponse
        return new Response($response);
    }
}
