<?php

namespace CaseStoreBundle\Controller;


use CaseStoreBundle\Entity\OutputDocument;
use CaseStoreBundle\Form\Type\OutputLinkExistingCaseStudyType;
use CaseStoreOutputFieldTypeStringBundle\Entity\OutputFieldValueString;
use CaseStoreOutputFieldTypeTextBundle\Entity\OutputFieldValueText;
use CaseStoreBundle\Form\Type\OutputDocumentNewType;
use CaseStoreBundle\Form\Type\OutputFieldValueStringEditType;
use CaseStoreBundle\Form\Type\OutputFieldValueTextEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class OutputEditController extends OutputController
{


    public function editFieldAction($projectId, $outputId, $fieldDefinitionId, Request $request)
    {

        $doctrine = $this->getDoctrine()->getManager();

        // build
        $this->build($projectId, $outputId);

        $fieldDefinition = $doctrine->getRepository('CaseStoreBundle:OutputFieldDefinition')->findOneBy(array('project'=>$this->project,'publicId'=>$fieldDefinitionId));
        if (!$fieldDefinition) {
            throw new  NotFoundHttpException('Not found');
        }

        //data
        if ($fieldDefinition->isTypeString()) {
            $value = new OutputFieldValueString();
            $value->setFieldDefinition($fieldDefinition);
            $value->setOutput($this->output);
            $value->setAddedBy($this->getUser());
            $oldValue = $doctrine->
                getRepository('CaseStoreOutputFieldTypeStringBundle:OutputFieldValueString')->
                getLatestValueFor($fieldDefinition, $this->output);
            $form = $this->createForm(new OutputFieldValueStringEditType($fieldDefinition, $oldValue), $value);
            if ($request->getMethod() == 'POST') {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $doctrine->persist($value);
                    $doctrine->flush();
                    $doctrine->getRepository('CaseStoreBundle:Output')->updateCaches($this->output);
                    return $this->redirect($this->generateUrl('case_store_output', array(
                        'projectId'=>$this->project->getPublicId(),
                        'outputId'=>$this->output->getPublicId(),
                    )));
                }
            }
        } else if ($fieldDefinition->isTypeText()) {
            $value = new OutputFieldValueText();
            $value->setFieldDefinition($fieldDefinition);
            $value->setOutput($this->output);
            $value->setAddedBy($this->getUser());
            $oldValue = $doctrine->
                getRepository('CaseStoreOutputFieldTypeTextBundle:OutputFieldValueText')->
                getLatestValueFor($fieldDefinition, $this->output);
            $form = $this->createForm(new OutputFieldValueTextEditType($fieldDefinition, $oldValue), $value);
            if ($request->getMethod() == 'POST') {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $doctrine->persist($value);
                    $doctrine->flush();
                    $doctrine->getRepository('CaseStoreBundle:Output')->updateCaches($this->output);
                    return $this->redirect($this->generateUrl('case_store_output', array(
                        'projectId'=>$this->project->getPublicId(),
                        'outputId'=>$this->output->getPublicId(),
                    )));
                }
            }
        } else {
            throw new  NotFoundHttpException('Type Not found');
        }

        return $this->render('CaseStoreBundle:OutputEdit:editField.html.twig', array(
            'project'=>$this->project,
            'output'=>$this->output,
            'fieldDefinition'=>$fieldDefinition,
            'form' => $form->createView(),
        ));

    }


    public function newDocumentAction($projectId, $outputId)
    {
        $this->build($projectId, $outputId);

        $doctrine = $this->getDoctrine()->getManager();

        $outputDocument = new OutputDocument();
        $outputDocument->setOutput($this->output);
        $outputDocument->setAddedBy($this->getUser());

        $form = $this->createForm(new OutputDocumentNewType(), $outputDocument);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $outputDocument->upload();
                $doctrine->persist($outputDocument);
                $doctrine->flush();
                return $this->redirect($this->generateUrl('case_store_output', array(
                    'projectId'=>$this->project->getPublicId(),
                    'outputId'=>$this->output->getPublicId(),
                )));
            }
        }

        return $this->render('CaseStoreBundle:OutputEdit:newDocument.html.twig', array(
            'project' => $this->project,
            'output' => $this->output,
            'form' => $form->createView(),
        ));

    }

    public function linkCaseStudyAction($projectId, $outputId)
    {
        $this->build($projectId, $outputId);


        $doctrine = $this->getDoctrine()->getManager();


        $form = $this->createForm(new OutputLinkExistingCaseStudyType());
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $caseStudy = $doctrine->getRepository('CaseStoreBundle:CaseStudy')->findOneBy(array('project'=>$this->project, 'publicId'=>$form->get('public_id')->getData()));
                if ($caseStudy) {
                    $doctrine->getRepository('CaseStoreBundle:CaseStudyHasOutput')->addLink($caseStudy, $this->output, $this->getUser());
                    return $this->redirect($this->generateUrl('case_store_output', array(
                        'projectId'=>$this->project->getPublicId(),
                        'outputId'=>$this->output->getPublicId(),
                    )));
                }
            }
        }



        return $this->render('CaseStoreBundle:OutputEdit:linkCaseStudy.html.twig', array(
            'project' => $this->project,
            'output' => $this->output,
            'form' => $form->createView(),
        ));

    }

}
