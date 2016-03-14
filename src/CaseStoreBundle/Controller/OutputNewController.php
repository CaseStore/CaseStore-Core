<?php

namespace CaseStoreBundle\Controller;

use CaseStoreBundle\Entity\CaseStudyHasOutput;
use CaseStoreBundle\Entity\Output;
use CaseStoreBundle\Entity\OutputFieldValueString;
use CaseStoreBundle\Entity\OutputFieldValueText;
use CaseStoreBundle\Form\Type\OutputNewType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class OutputNewController extends ProjectController
{

    public function newOutputAction($projectId, Request $request)
    {
        $this->build($projectId);

        if (!$this->getUser()) {
            throw new  NotFoundHttpException('Not found');
        }

        $doctrine = $this->getDoctrine()->getManager();

        $caseStudy = null;
        if ($request->query->get('caseStudy')) {
            $caseStudy = $doctrine->getRepository('CaseStoreBundle:CaseStudy')->findOneBy(array('project'=>$this->project, 'publicId'=>$request->query->get('caseStudy')));
        }

        $output = new Output();
        $output->setProject($this->project);

        $form = $this->createForm(new OutputNewType($doctrine, $this->project));
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $doctrine->persist($output);

                $outputFieldDefinitions = $doctrine->getRepository('CaseStoreBundle:OutputFieldDefinition')->getForProject($this->project);

                foreach($outputFieldDefinitions as $outputFieldDefinition) {
                    if ($outputFieldDefinition->isTypeString()) {

                        $fieldValue = new OutputFieldValueString();
                        $fieldValue->setFieldDefinition($outputFieldDefinition);
                        $fieldValue->setOutput($output);
                        $fieldValue->setAddedBy($this->getUser());
                        $fieldValue->setValue($form['field_'.$outputFieldDefinition->getPublicId()]->getData());
                        $doctrine->persist($fieldValue);

                    } else if ($outputFieldDefinition->istypeText()) {

                        $fieldValue = new OutputFieldValueText();
                        $fieldValue->setFieldDefinition($outputFieldDefinition);
                        $fieldValue->setOutput($output);
                        $fieldValue->setAddedBy($this->getUser());
                        $fieldValue->setValue($form['field_'.$outputFieldDefinition->getPublicId()]->getData());
                        $doctrine->persist($fieldValue);

                    }
                }


                if ($caseStudy) {
                    $caseStudyHasOutput = new CaseStudyHasOutput();
                    $caseStudyHasOutput->setOutput($output);
                    $caseStudyHasOutput->setAddedBy($this->getUser());
                    $caseStudyHasOutput->setCaseStudy($caseStudy);
                    $doctrine->persist($caseStudyHasOutput);
                }

                $doctrine->flush();

                // Update Caches now!
                // TODO $doctrine->getRepository('CaseStoreBundle:Output')->updateCaches($output);


                return $this->redirect($this->generateUrl('case_store_output', array(
                    'projectId'=>$this->project->getPublicId(),
                    'outputId'=>$output->getPublicId(),
                    )));
            }
        }

        return $this->render('CaseStoreBundle:OutputNew:index.html.twig', array(
            'project' => $this->project,
            'form' => $form->createView(),
            'caseStudy' => $caseStudy,
        ));

    }



}
