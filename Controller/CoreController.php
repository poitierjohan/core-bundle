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

class CoreController extends Controller
{
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('dywee_cms_homepage'));
    }

    public function testMailAction()
    {
        $or = $this->getDoctrine()->getManager()->getRepository('DyweeOrderBundle:BaseOrder');
        $order = $or->findOneById(398);

        if($order)
        {
            $data = array('order' => $order);

            if($order->getDeliveryMethod() == '24R')
            {
                $client = new \nusoap_client('http://www.mondialrelay.fr/WebService/Web_Services.asmx?WSDL', true);

                $explode = explode('-', $order->getDeliveryInfo());

                $params = array(
                    'Enseigne' => "BEBLCBLC",
                    'Num' => $explode[1],
                    'Pays' => $explode[0]
                );

                $security = '';
                foreach($params as $param)
                    $security .= $param;
                $security .= 'xgG1mpth';

                $params['Security'] = strtoupper(md5($security));

                $result = $client->call('WSI2_AdressePointRelais', $params, 'http://www.mondialrelay.fr/webservice/', 'http://www.mondialrelay.fr/webservice/WSI2_AdressePointRelais');

                if($result['WSI2_AdressePointRelaisResult']['STAT'] == 0)
                {
                    $data['relais'] = array(
                        'address1'  => $result['WSI2_AdressePointRelaisResult']['LgAdr1'],
                        'address2'  => $result['WSI2_AdressePointRelaisResult']['LgAdr3'],
                        'zip'       => $result['WSI2_AdressePointRelaisResult']['CP'],
                        'cityString' => $result['WSI2_AdressePointRelaisResult']['Ville']
                    );
                }
            }


            return $this->render('DyweeOrderBundle:Email:mail-step2.html.twig', $data);
        }
        else throw $this->createNotFoundException('Commande introuvable');

    }
        /*$or = $this->getDoctrine()->getManager()->getRepository('DyweeOrderBundle:BaseOrder');
        $order = $or->findOneById(285);

        if($order != null) {
            $fileName = str_replace(' ', '_', $order->getInvoiceReference()) . '.pdf';
            if (!file_exists($fileName)) {
                $bill = $this->renderView('DyweeOrderBundle:Order:invoice.html.twig', array(
                    'order' => $order
                ));

                $pdfGenerator = $this->get('spraed.pdf.generator');

                return new Response($pdfGenerator->generatePDF($bill, 'UTF-8'),
                    200,
                    array(
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'inline; filename="'.$fileName.'"'
                    )
                );
            }
        }

        $attachment = Swift_Attachment::newInstance($pdf, $fileName, 'application/pdf');

        $message = \Swift_Message::newInstance()
            ->setSubject('Confirmation de votre paiement')
            ->setFrom('info@labelgiqueunefois.be')
            ->setTo('olivier.delbruyere@hotmail.com')
            ->setBody($this->renderView('DyweeOrderBundle:Email:mail-step2.html.twig', array('order' => $order)))
            ->attach($attachment)
        ;
        $message->setContentType("text/html");

        return new Response('mail envoyé');*/

    public function robotsTxtAction()
    {
        return $this->render('DyweeCoreBundle::robots.txt.twig');
    }
}
