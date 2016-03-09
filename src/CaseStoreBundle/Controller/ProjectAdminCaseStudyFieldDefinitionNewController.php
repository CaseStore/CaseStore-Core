<?php

namespace CaseStoreBundle\Controller;

use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyFieldDefinition;
use CaseStoreBundle\Entity\Project;
use CaseStoreBundle\Form\Type\CaseStudyFieldDefinitionNewType;
use CaseStoreBundle\Form\Type\CaseStudyNewType;
use CaseStoreBundle\Form\Type\ProjectNewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class ProjectAdminCaseStudyFieldDefinitionNewController extends ProjectAdminController
{



    public function indexAction($projectId)
    {
        $this->build($projectId);

        if (!$this->getUser()) {
            throw new  NotFoundHttpException('Not found');
        }

        $doctrine = $this->getDoctrine()->getManager();

        $caseStudyFieldDefinition = new CaseStudyFieldDefinition();
        $caseStudyFieldDefinition->setProject($this->project);
        $caseStudyFieldDefinition->setAddedBy($this->getUser());

        $form = $this->createForm(new CaseStudyFieldDefinitionNewType(), $caseStudyFieldDefinition);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $caseStudyFieldDefinition->setSort($doctrine->getRepository('CaseStoreBundle:CaseStudyFieldDefinition')->getNextSortOrderForNewFieldOnProject($this->project));
                $doctrine->persist($caseStudyFieldDefinition);
                $doctrine->flush();
                return $this->redirect($this->generateUrl('case_store_project_admin_case_study_field_definition', array(
                    'projectId'=>$this->project->getPublicId(),
                    'caseStudyFieldDefinitionId'=>$caseStudyFieldDefinition->getPublicId(),
                    )));
            }
        }

        return $this->render('CaseStoreBundle:ProjectAdminCaseStudyFieldDefinitionNew:index.html.twig', array(
            'project' => $this->project,
            'form' => $form->createView(),
        ));

    }



}
