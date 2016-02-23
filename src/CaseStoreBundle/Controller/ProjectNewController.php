<?php

namespace CaseStoreBundle\Controller;

use CaseStoreBundle\Entity\Project;
use CaseStoreBundle\Form\Type\ProjectNewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class ProjectNewController extends Controller
{
    public function indexAction()
    {

        $doctrine = $this->getDoctrine()->getManager();

        $project = new Project();
        $project->setOwner($this->getUser());

        $form = $this->createForm(new ProjectNewType(), $project);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $doctrine->persist($project);
                $doctrine->flush();
                return $this->redirect($this->generateUrl('case_store_project', array('projectId'=>$project->getPublicId())));
            }
        }

        return $this->render('CaseStoreBundle:ProjectNew:index.html.twig', array(
            'form' => $form->createView(),
        ));

    }

}
