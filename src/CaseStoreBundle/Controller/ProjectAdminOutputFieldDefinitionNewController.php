<?php

namespace CaseStoreBundle\Controller;

use CaseStoreBundle\Entity\Output;
use CaseStoreBundle\Entity\OutputFieldDefinition;
use CaseStoreBundle\Entity\Project;
use CaseStoreBundle\Form\Type\OutputFieldDefinitionNewType;
use CaseStoreBundle\Form\Type\OutputNewType;
use CaseStoreBundle\Form\Type\ProjectNewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class ProjectAdminOutputFieldDefinitionNewController extends ProjectAdminController
{



    public function indexAction($projectId)
    {
        $this->build($projectId);

        if (!$this->getUser()) {
            throw new  NotFoundHttpException('Not found');
        }

        $doctrine = $this->getDoctrine()->getManager();

        $outputFieldDefinition = new OutputFieldDefinition();
        $outputFieldDefinition->setProject($this->project);
        $outputFieldDefinition->setAddedBy($this->getUser());

        $form = $this->createForm(new OutputFieldDefinitionNewType(), $outputFieldDefinition);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $outputFieldDefinition->setSort($doctrine->getRepository('CaseStoreBundle:OutputFieldDefinition')->getNextSortOrderForNewFieldOnProject($this->project));
                $doctrine->persist($outputFieldDefinition);
                $doctrine->flush();
                return $this->redirect($this->generateUrl('case_store_project_admin_output_field_definition', array(
                    'projectId'=>$this->project->getPublicId(),
                    'outputFieldDefinitionId'=>$outputFieldDefinition->getPublicId(),
                    )));
            }
        }

        return $this->render('CaseStoreBundle:ProjectAdminOutputFieldDefinitionNew:index.html.twig', array(
            'project' => $this->project,
            'form' => $form->createView(),
        ));

    }



}
