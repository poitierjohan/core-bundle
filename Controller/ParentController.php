<?php

namespace Dywee\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/*
 * v1.1 by Olivier:
 *      ajout de tableAction, ajout d'un argument $parameters à chaque méthode pour pouvoir gérer et complexifier un peu le bazar
 *      indexAction est nécessaire? Il renvoie un tableau -> tableAction?
 *
 *
 */

trait Referer {
    private function getRefererParams() {
        $request = $this->getRequest();
        $referer = $request->headers->get('referer');
        $baseUrl = $request->getBaseUrl();
        $lastPath = substr($referer, strpos($referer, $baseUrl) + strlen($baseUrl));
        return $this->get('router')->getMatcher()->match($lastPath);
    }
}


class ParentController extends Controller
{
    use Referer;
    private $repositoryName;

    public function __construct()
    {
        $this->repositoryName = $this->bundleName.':'.$this->entityName;
    }

    public function getEntityNameSpace()
    {
        return $this->bundleName.'\Entity\\'.$this->entityName;
    }

    public function viewAction($id, $parameters = null)
    {
        $object = $this->getFromId($id);

        return $this->handleView(array('view' => 'view', 'data' => array(lcfirst($this->entityName) => $object)), $parameters);
    }

    public function tableAction($parameters = null)
    {
        return $this->handleView(array(
            'view' => 'table',
            'data' => array(
                lcfirst($this->entityName).'List' => $this->getList($parameters)
            )),
            $parameters
        );
    }

    public function dashboardAction($parameters = null)
    {
        return $this->handleView(array(
            'view' => 'dashboard',
            'data' => array(
                lcfirst($this->entityName).'List' => $this->getList($parameters)
            )),
            $parameters
        );
    }

    private function getList($parameters)
    {
        $repositoryMethod = isset($parameters['repositoryMethod']) ? $parameters['repositoryMethod'] : 'findBy';
        $findBy = $parameters['findBy'] ? $parameters['findBy'] : array();
        $orderBy = $parameters['orderBy'] ? $parameters['orderBy'] : array();

        $repository = $this->getDoctrine()->getRepository($this->repositoryName);
        $items = $repository->findBy($findBy, $orderBy);

        //var_dump($items);
        return $items;
    }

    public function addAction(Request $request, $parameters = null)
    {
        $entityName = $this->getEntityNameSpace();

        return $this->handleForm(new $entityName(), $request, $parameters);
    }

    public function updateAction($id, Request $request, $parameters = null)
    {
        $object = $this->getFromId($id);

        return $this->handleForm($object, $request, $parameters);
    }

    //Créer/gère le formulaire + ajout/modif dans la BDD
    protected function handleForm($object, Request $request, $parameters = null)
    {
        $bundleName = isset($parameters['bundleFormName']) ? $parameters['bundleFormName'] : $this->bundleName;
        $entityName = isset($parameters['entityFormName']) ? $parameters['entityFormName'] : $this->entityName;

        $type = $bundleName.'\Form\\'.$entityName.'Type';

        $form = $this->get('form.factory')->createBuilder($type, $object)->getForm();


        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();

            //Méthode d'execution automatique de PrePersist
            //if(method_exists($object, 'prePersist'))
            //    $object->prePersist();

            $em->persist($object);
            $em->flush();

            $request->getSession()->getFlashBag()->set('success', $this->publicName . ' correctement ' . ($new ? 'ajouté' : 'modifié'));

            if(isset($parameters['redirectTo']))
            {
                if($parameters['redirectTo'] == 'referer')
                    return $this->redirect($this->getPreviousRoute($request));
                if(isset($parameters['routingArgs']))
                    if(isset($parameters['routingArgs']['id']) && $parameters['routingArgs']['id'] == 'object_id')
                        $parameters['routingArgs']['id'] = $object->getId();

                return $this->redirect($this->generateUrl($parameters['redirectTo'], isset($parameters['routingArgs']) ? $parameters['routingArgs'] : null));
            }

            if (method_exists($object, 'getParentEntity') && $object->getParentEntity()->getId())
                return $this->redirect($this->generateUrl($this->tableViewName, array('id' => $object->getParentEntity()->getId())));
            else
                return $this->redirect($this->generateUrl($this->tableViewName));
        }

        $viewFolder = isset($parameters['viewFolderName']) ? $parameters['viewFolderName'] : $entityName;

        if($new)
            return $this->render($bundleName.':'.$viewFolder.':add.html.twig', ['form' => $form->createView()]);
        else
            return $this->render($bundleName.':'.$viewFolder.':edit.html.twig', ['form' => $form->createView()]);
    }

    public function tableFromParentAction($id, $parameters = null)
    {
        $entityName = $this->getEntityNameSpace();
        $childEntity = new $entityName();

        //On récupère l'entité parente
        $explodedNameSpace = explode('\\', get_class($childEntity->getParentEntity()));
        $parentRepository = $this->getDoctrine()->getRepository($explodedNameSpace[0].':'.$explodedNameSpace[2]);
        $parentEntity = $parentRepository->findOneById($id);


        //On récupère la liste des entités enfants à partir de l'entité parente
        $repository = $this->getDoctrine()->getRepository($this->repositoryName);

        if($this->entityName == $explodedNameSpace[2])
            $methodName = 'findByParent';
        else $methodName = 'findBy'.$explodedNameSpace[2];

        $items = $repository->$methodName($parentEntity);

        return $this->handleView(array(
            'view' => 'table',
            'data' => array(
                lcfirst($explodedNameSpace[2]) => $parentEntity
            )),
            $parameters
        );
    }

    public function addFromParentAction($id, Request $request, $parameters = null)
    {
        $childEntityName = $this->getEntityNameSpace();
        $childEntity = new $childEntityName();

        //On récupère l'entité parente
        $explodedNameSpace = explode('\\', get_class($childEntity->getParentEntity()));
        $parentRepository = $this->getDoctrine()->getRepository($explodedNameSpace[0].':'.$explodedNameSpace[2]);
        $parentEntity = $parentRepository->findOneById($id);

        //On set l'entité parente dans l'entité à ajouter
        if($this->entityName == $explodedNameSpace[2])
            $methodParentName = 'setParent';
        else $methodParentName = 'set'.$explodedNameSpace[2];

        //print_r($parentEntity); exit;

        $childEntity->$methodParentName($parentEntity);

        $parameters['redirectUrlParameters'] = array('id' => $parentEntity->getId());

        return $this->handleForm($childEntity, $request, $parameters);
    }

    public function deleteAction($id, Request $request, $parameters = null)
    {
        $item = $this->getFromId($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($item);

        $em->flush();

        $message = 'Element bien supprimé';

        if($request->isXmlHttpRequest())
            return new Response(json_encode(array('type' => 'success', 'content' => $message)));

        $this->get('session')->getFlashBag()->set('success', $message);

        return $this->redirect($this->generateUrl($this->tableViewName));
    }

    public function handleView($mainParameters, $parameters = null)
    {
        $parentPath = isset($parameters['viewFolder']) ? $this->bundleName.':'.$parameters['viewFolder'] : $this->repositoryName;
        $fileName = isset($mainParameters['view']) ? $mainParameters['view'] : 'dashboard';
        $data =
            array_merge(
                isset($mainParameters['data']) ? $mainParameters['data'] : array(),
                isset($parameters['add_data']) ? $parameters['add_data'] : array()
            )
        ;
        return $this->render($parentPath.':'.$fileName.'.html.twig', $data);
    }

    public function getPreviousRoute($request)
    {
        return $request->server->get('HTTP_REFERER');
    }

    public function getFromId($id)
    {
        $repository = $this->getDoctrine()->getRepository($this->repositoryName);
        $object = $repository->findOneById($id);

        if(!$object)
            throw $this->createNotFoundException('Objet non trouvé');

        return $object;
    }

    public function tableHelper()
    {

    }

    public function tableActionsHelper()
    {

    }
}