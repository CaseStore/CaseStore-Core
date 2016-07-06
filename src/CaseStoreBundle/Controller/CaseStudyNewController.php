<?php

namespace CaseStoreBundle\Controller;

use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreCaseStudyFieldTypeIntegerBundle\Entity\CaseStudyFieldValueInteger;
use CaseStoreCaseStudyFieldTypeSelectBundle\Entity\CaseStudyFieldValueSelect;
use CaseStoreCaseStudyFieldTypeStringBundle\Entity\CaseStudyFieldValueString;
use CaseStoreCaseStudyFieldTypeTextBundle\Entity\CaseStudyFieldValueText;
use CaseStoreBundle\Entity\CaseStudyHasUser;
use CaseStoreBundle\Entity\Project;
use CaseStoreBundle\Form\Type\CaseStudyNewType;
use CaseStoreBundle\Form\Type\ProjectNewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyNewController extends ProjectController
{

    public function indexAction($projectId)
    {
        $this->build($projectId);

        if (!$this->getUser()) {
            throw new  NotFoundHttpException('Not found');
        }

        $doctrine = $this->getDoctrine()->getManager();

        $casestudy = new CaseStudy();
        $casestudy->setProject($this->project);

        $form = $this->createForm(new CaseStudyNewType($doctrine, $this->project));
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $doctrine->persist($casestudy);

                $caseStudyFieldDefinitions = $doctrine->getRepository('CaseStoreBundle:CaseStudyFieldDefinition')->getForProject($this->project);

                foreach($caseStudyFieldDefinitions as $caseStudyFieldDefinition) {
                    if ($caseStudyFieldDefinition->isTypeString()) {

                        $fieldValue = new CaseStudyFieldValueString();
                        $fieldValue->setFieldDefinition($caseStudyFieldDefinition);
                        $fieldValue->setCaseStudy($casestudy);
                        $fieldValue->setAddedBy($this->getUser());
                        $fieldValue->setValue($form['field_'.$caseStudyFieldDefinition->getPublicId()]->getData());
                        $doctrine->persist($fieldValue);

                    } else if ($caseStudyFieldDefinition->istypeText()) {

                        $fieldValue = new CaseStudyFieldValueText();
                        $fieldValue->setFieldDefinition($caseStudyFieldDefinition);
                        $fieldValue->setCaseStudy($casestudy);
                        $fieldValue->setAddedBy($this->getUser());
                        $fieldValue->setValue($form['field_'.$caseStudyFieldDefinition->getPublicId()]->getData());
                        $doctrine->persist($fieldValue);

                    } else if ($caseStudyFieldDefinition->isTypeInteger()) {

                        $fieldValue = new CaseStudyFieldValueInteger();
                        $fieldValue->setFieldDefinition($caseStudyFieldDefinition);
                        $fieldValue->setCaseStudy($casestudy);
                        $fieldValue->setAddedBy($this->getUser());
                        $fieldValue->setValue($form['field_'.$caseStudyFieldDefinition->getPublicId()]->getData());
                        $doctrine->persist($fieldValue);

                    } else if ($caseStudyFieldDefinition->isTypeSelect()) {

                        foreach($form['field_'.$caseStudyFieldDefinition->getPublicId()]->getData() as $option) {

                            $fieldValue = new CaseStudyFieldValueSelect();
                            $fieldValue->setFieldDefinition($caseStudyFieldDefinition);
                            $fieldValue->setCaseStudy($casestudy);
                            $fieldValue->setAddedBy($this->getUser());
                            $fieldValue->setOption($option);
                            $doctrine->persist($fieldValue);

                        }


                    }
                }


                $caseStudyHasUser = new CaseStudyHasUser();
                $caseStudyHasUser->setUser($this->getUser());
                $caseStudyHasUser->setAddedBy($this->getUser());
                $caseStudyHasUser->setCaseStudy($casestudy);
                $doctrine->persist($caseStudyHasUser);


                $doctrine->flush();

                // Update Caches now!
                $doctrine->getRepository('CaseStoreBundle:CaseStudy')->updateCaches($casestudy);


                return $this->redirect($this->generateUrl('case_store_case_study', array(
                    'projectId'=>$this->project->getPublicId(),
                    'caseStudyId'=>$casestudy->getPublicId(),
                    )));
            }
        }

        return $this->render('CaseStoreBundle:CaseStudyNew:index.html.twig', array(
            'project' => $this->project,
            'form' => $form->createView(),
        ));

    }



}
