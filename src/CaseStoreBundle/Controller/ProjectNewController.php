<?php

namespace CaseStoreBundle\Controller;

use CaseStoreBundle\Entity\CaseStudyFieldDefinition;
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

                $fieldDefTitle = new CaseStudyFieldDefinition();
                $fieldDefTitle->setProject($project);
                $fieldDefTitle->setAddedBy($this->getUser());
                $fieldDefTitle->setSort(0);
                $fieldDefTitle->setType('string');
                $fieldDefTitle->setTitle('Title');
                $fieldDefTitle->setPublicId('title');
                $doctrine->persist($fieldDefTitle);

                $fieldDefDescription = new CaseStudyFieldDefinition();
                $fieldDefDescription->setProject($project);
                $fieldDefDescription->setAddedBy($this->getUser());
                $fieldDefDescription->setSort(1);
                $fieldDefDescription->setType('text');
                $fieldDefDescription->setTitle('Description');
                $fieldDefDescription->setPublicId('description');
                $doctrine->persist($fieldDefDescription);

                $doctrine->flush();
                return $this->redirect($this->generateUrl('case_store_project', array('projectId'=>$project->getPublicId())));
            }
        }

        return $this->render('CaseStoreBundle:ProjectNew:index.html.twig', array(
            'form' => $form->createView(),
        ));

    }

}
