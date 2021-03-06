<?php

namespace CaseStoreBundle\Controller;

use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyComment;
use CaseStoreBundle\Entity\CaseStudyDocument;
use CaseStoreBundle\Form\Type\CaseStudyEditType;
use CaseStoreCaseStudyFieldTypeIntegerBundle\Entity\CaseStudyFieldValueInteger;
use CaseStoreCaseStudyFieldTypeSelectBundle\Entity\CaseStudyFieldValueSelect;
use CaseStoreCaseStudyFieldTypeStringBundle\Entity\CaseStudyFieldValueString;
use CaseStoreCaseStudyFieldTypeTextBundle\Entity\CaseStudyFieldValueText;
use CaseStoreBundle\Entity\CaseStudyHasLocation;
use CaseStoreBundle\Entity\CaseStudyLocation;
use CaseStoreBundle\Entity\Project;
use CaseStoreBundle\Form\Type\CaseStudyCommentNewType;
use CaseStoreBundle\Form\Type\CaseStudyDocumentNewType;
use CaseStoreBundle\Form\Type\CaseStudyFieldValueIntegerEditType;
use CaseStoreBundle\Form\Type\CaseStudyFieldValueSelectEditType;
use CaseStoreBundle\Form\Type\CaseStudyFieldValueStringEditType;
use CaseStoreBundle\Form\Type\CaseStudyFieldValueTextEditType;
use CaseStoreBundle\Form\Type\ProjectNewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyEditController extends CaseStudyController
{

    protected function build($projectId, $caseStudyId) {
        parent::build($projectId, $caseStudyId);
        if (!$this->editAccessAllowed) {
            throw new  NotFoundHttpException('Not edit');
        }
    }

    public function newDocumentAction($projectId, $caseStudyId)
    {
        $this->build($projectId, $caseStudyId);

        $doctrine = $this->getDoctrine()->getManager();

        $caseStudyDocument = new CaseStudyDocument();
        $caseStudyDocument->setCaseStudy($this->caseStudy);
        $caseStudyDocument->setAddedBy($this->getUser());

        $form = $this->createForm(new CaseStudyDocumentNewType(), $caseStudyDocument);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $caseStudyDocument->upload();
                $doctrine->persist($caseStudyDocument);
                $doctrine->flush();
                return $this->redirect($this->generateUrl('case_store_case_study', array(
                    'projectId'=>$this->project->getPublicId(),
                    'caseStudyId'=>$this->caseStudy->getPublicId(),
                )));
            }
        }

        return $this->render('CaseStoreBundle:CaseStudyEdit:newDocument.html.twig', array(
            'project' => $this->project,
            'caseStudy' => $this->caseStudy,
            'form' => $form->createView(),
        ));

    }


    public function editUsersAction($projectId, $caseStudyId, Request $request)
    {
        // build
        $this->build($projectId, $caseStudyId);

        //data
        $doctrine = $this->getDoctrine()->getManager();
        $userRepo = $doctrine->getRepository('CaseStoreBundle:User');

        if ($request->getMethod() == 'POST') {
            $user = $userRepo->findOneBy(array('email'=>$request->request->get('email')));
            if ($user) {
                if ($request->request->get('action') == 'add') {
                    $userRepo->addUserToCaseStudy($user, $this->caseStudy, $this->getUser());
                    // TODO Redirect to page to avoid page reload problem
                } elseif ($request->request->get('action') == 'remove') {
                    $userRepo->removeUserFromCaseStudy($user, $this->caseStudy, $this->getUser());
                    // TODO Redirect to page to avoid page reload problem
                }
            }
        }

        $allUsers = $userRepo->findAll();
        $currentUsers =  $userRepo->findByCaseStudy($this->caseStudy);

        return $this->render('CaseStoreBundle:CaseStudyEdit:editUsers.html.twig', array(
            'project'=>$this->project,
            'caseStudy'=>$this->caseStudy,
            'allUsers'=>$allUsers,
            'currentUsers'=>$currentUsers,
        ));
    }

    public function editLocationsAction($projectId, $caseStudyId, Request $request)
    {
        // build
        $this->build($projectId, $caseStudyId);

        //data
        $doctrine = $this->getDoctrine()->getManager();


        if ($request->getMethod() == 'POST') {
            if ($request->request->get('action') == 'add') {
                $lat = floatval($request->request->get('lat'));
                $lng = floatval($request->request->get('lng'));
                if ($lat != 0 || $lat != 0) {
                    $caseStudyLocation = new CaseStudyLocation();
                    $caseStudyLocation->setCaseStudy($this->caseStudy);
                    $caseStudyLocation->setAddedBy($this->getUser());
                    $caseStudyLocation->setLat($lat);
                    $caseStudyLocation->setLng($lng);
                    $doctrine->persist($caseStudyLocation);
                    $doctrine->flush($caseStudyLocation);
                    return $this->redirect($this->generateUrl('case_store_case_study', array(
                        'projectId'=>$this->project->getPublicId(),
                        'caseStudyId'=>$this->caseStudy->getPublicId(),
                    )));
                }
            } else if ($request->request->get('action') == 'remove') {
                $location = $doctrine->getRepository('CaseStoreBundle:CaseStudyLocation')->findOneBy(array('caseStudy'=>$this->caseStudy,'publicId'=>$request->request->get('id')));
                if ($location && !$location->isRemoved()) {
                    $location->setRemovedAt(new \DateTime());
                    $location->setRemovedBy($this->getUser());
                    $doctrine->persist($location);
                    $doctrine->flush($location);
                    return $this->redirect($this->generateUrl('case_store_case_study', array(
                        'projectId'=>$this->project->getPublicId(),
                        'caseStudyId'=>$this->caseStudy->getPublicId(),
                    )));
                }

            }

        }

        $locations =  $doctrine->getRepository('CaseStoreBundle:CaseStudyLocation')->findBy(array('caseStudy'=>$this->caseStudy,'removedAt'=>null));


        return $this->render('CaseStoreBundle:CaseStudyEdit:editLocations.html.twig', array(
            'project'=>$this->project,
            'caseStudy'=>$this->caseStudy,
            'locations'=>$locations,
        ));
    }

    public function editAction($projectId, $caseStudyId, Request $request)
    {

        $doctrine = $this->getDoctrine()->getManager();

        // build
        $this->build($projectId, $caseStudyId);

        $formObject = new CaseStudyEditType($this->container, $this->project, $this->caseStudy);
        $form = $this->createForm($formObject);
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {

                foreach($formObject->getCaseStudyFieldDefinitions() as $caseStudyFieldDefinition) {

                    if ($caseStudyFieldDefinition->isTypeString()) {

                        $oldValue = $this->container->get('case_study_field_type_finder')->getFieldTypeById($caseStudyFieldDefinition->getType())->getLatestValue($caseStudyFieldDefinition, $this->caseStudy);

                        $valueObject = new CaseStudyFieldValueString();
                        $valueObject->setFieldDefinition($caseStudyFieldDefinition);
                        $valueObject->setCaseStudy($this->caseStudy);
                        $valueObject->setAddedBy($this->getUser());
                        $valueObject->setValue($form->get('field_' . $caseStudyFieldDefinition->getPublicId())->getData());
                        if ($valueObject->isDifferentFrom($oldValue)) {
                            $doctrine->persist($valueObject);
                        }

                    } else if ($caseStudyFieldDefinition->isTypeText()) {

                        $oldValue = $this->container->get('case_study_field_type_finder')->getFieldTypeById($caseStudyFieldDefinition->getType())->getLatestValue($caseStudyFieldDefinition, $this->caseStudy);

                        $valueObject = new CaseStudyFieldValueText();
                        $valueObject->setFieldDefinition($caseStudyFieldDefinition);
                        $valueObject->setCaseStudy($this->caseStudy);
                        $valueObject->setAddedBy($this->getUser());
                        $valueObject->setValue($form->get('field_' . $caseStudyFieldDefinition->getPublicId())->getData());
                        if ($valueObject->isDifferentFrom($oldValue)) {
                            $doctrine->persist($valueObject);
                        }

                    }
                }

                $doctrine->flush();
                $doctrine->getRepository('CaseStoreBundle:CaseStudy')->updateCaches($this->caseStudy);
                return $this->redirect($this->generateUrl('case_store_case_study', array(
                    'projectId'=>$this->project->getPublicId(),
                    'caseStudyId'=>$this->caseStudy->getPublicId(),
                )));
            }
        }
        return $this->render('CaseStoreBundle:CaseStudyEdit:edit.html.twig', array(
            'project'=>$this->project,
            'caseStudy'=>$this->caseStudy,
            'form' => $form->createView(),
        ));

    }

    public function editFieldAction($projectId, $caseStudyId, $fieldDefinitionId, Request $request)
    {

        $doctrine = $this->getDoctrine()->getManager();

        // build
        $this->build($projectId, $caseStudyId);

        $fieldDefinition = $doctrine->getRepository('CaseStoreBundle:CaseStudyFieldDefinition')->findOneBy(array('project'=>$this->project,'publicId'=>$fieldDefinitionId));
        if (!$fieldDefinition) {
            throw new  NotFoundHttpException('Not found');
        }

        //data
        if ($fieldDefinition->isTypeString()) {
            $value = new CaseStudyFieldValueString();
            $value->setFieldDefinition($fieldDefinition);
            $value->setCaseStudy($this->caseStudy);
            $value->setAddedBy($this->getUser());
            $oldValue = $doctrine->
                getRepository('CaseStoreCaseStudyFieldTypeStringBundle:CaseStudyFieldValueString')->
                getLatestValueFor($fieldDefinition, $this->caseStudy);
            $form = $this->createForm(new CaseStudyFieldValueStringEditType($fieldDefinition, $oldValue), $value);
            if ($request->getMethod() == 'POST') {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $doctrine->persist($value);
                    $doctrine->flush();
                    $this->get('case_study_field_type_finder')->getFieldTypeById($fieldDefinition->getType())->updateCaches($fieldDefinition, $this->caseStudy);
                    $doctrine->getRepository('CaseStoreBundle:CaseStudy')->updateCaches($this->caseStudy);
                    return $this->redirect($this->generateUrl('case_store_case_study', array(
                        'projectId'=>$this->project->getPublicId(),
                        'caseStudyId'=>$this->caseStudy->getPublicId(),
                    )));
                }
            }
        } else if ($fieldDefinition->isTypeText()) {
            $value = new CaseStudyFieldValueText();
            $value->setFieldDefinition($fieldDefinition);
            $value->setCaseStudy($this->caseStudy);
            $value->setAddedBy($this->getUser());
            $oldValue = $doctrine->
                getRepository('CaseStoreCaseStudyFieldTypeTextBundle:CaseStudyFieldValueText')->
                getLatestValueFor($fieldDefinition, $this->caseStudy);
            $form = $this->createForm(new CaseStudyFieldValueTextEditType($fieldDefinition, $oldValue), $value);
            if ($request->getMethod() == 'POST') {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $doctrine->persist($value);
                    $doctrine->flush();
                    $this->get('case_study_field_type_finder')->getFieldTypeById($fieldDefinition->getType())->updateCaches($fieldDefinition, $this->caseStudy);
                    $doctrine->getRepository('CaseStoreBundle:CaseStudy')->updateCaches($this->caseStudy);
                    return $this->redirect($this->generateUrl('case_store_case_study', array(
                        'projectId'=>$this->project->getPublicId(),
                        'caseStudyId'=>$this->caseStudy->getPublicId(),
                    )));
                }
            }
        } else if ($fieldDefinition->isTypeInteger()) {
            $value = new CaseStudyFieldValueInteger();
            $value->setFieldDefinition($fieldDefinition);
            $value->setCaseStudy($this->caseStudy);
            $value->setAddedBy($this->getUser());
            $oldValue = $doctrine->
                getRepository('CaseStoreCaseStudyFieldTypeIntegerBundle:CaseStudyFieldValueInteger')->
                getLatestValueFor($fieldDefinition, $this->caseStudy);
            $form = $this->createForm(new CaseStudyFieldValueIntegerEditType($fieldDefinition, $oldValue), $value);
            if ($request->getMethod() == 'POST') {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $doctrine->persist($value);
                    $doctrine->flush();
                    $this->get('case_study_field_type_finder')->getFieldTypeById($fieldDefinition->getType())->updateCaches($fieldDefinition, $this->caseStudy);
                    $doctrine->getRepository('CaseStoreBundle:CaseStudy')->updateCaches($this->caseStudy);
                    return $this->redirect($this->generateUrl('case_store_case_study', array(
                        'projectId'=>$this->project->getPublicId(),
                        'caseStudyId'=>$this->caseStudy->getPublicId(),
                    )));
                }
            }
        } else if ($fieldDefinition->isTypeSelect()) {
            $caseStudyFieldDefinitionOptionRepo = $doctrine->getRepository('CaseStoreBundle:CaseStudyFieldDefinitionOption');
            $choices = $caseStudyFieldDefinitionOptionRepo->findBy(array('fieldDefinition'=>$fieldDefinition));
            $oldValues = $doctrine->
                getRepository('CaseStoreCaseStudyFieldTypeSelectBundle:CaseStudyFieldValueSelect')->
                getLatestValuesFor($fieldDefinition, $this->caseStudy);
            $form = $this->createForm(new CaseStudyFieldValueSelectEditType($fieldDefinition, $choices, $oldValues));
            if ($request->getMethod() == 'POST') {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    foreach($choices as $choice) {
                        if (in_array($choice->getPublicId(), $form->get('value')->getData())) {
                            $doctrine->
                                getRepository('CaseStoreCaseStudyFieldTypeSelectBundle:CaseStudyFieldValueSelect')->
                                addOptionToCaseStudyField($choice, $this->caseStudy, $this->getUser());
                        } else {
                            $doctrine->
                                getRepository('CaseStoreCaseStudyFieldTypeSelectBundle:CaseStudyFieldValueSelect')->
                                removeOptionFromCaseStudyField($choice, $this->caseStudy, $this->getUser());
                        }
                    }
                    $doctrine->flush();
                    $this->get('case_study_field_type_finder')->getFieldTypeById($fieldDefinition->getType())->updateCaches($fieldDefinition, $this->caseStudy);
                    $doctrine->getRepository('CaseStoreBundle:CaseStudy')->updateCaches($this->caseStudy);
                    return $this->redirect($this->generateUrl('case_store_case_study', array(
                        'projectId'=>$this->project->getPublicId(),
                        'caseStudyId'=>$this->caseStudy->getPublicId(),
                    )));
                }
            }
        } else {
            throw new  NotFoundHttpException('Type Not found');
        }

        return $this->render('CaseStoreBundle:CaseStudyEdit:editField.html.twig', array(
            'project'=>$this->project,
            'caseStudy'=>$this->caseStudy,
            'fieldDefinition'=>$fieldDefinition,
            'form' => $form->createView(),
        ));

    }
    public function linkOutputAction($projectId, $caseStudyId, Request $request)
    {

        $doctrine = $this->getDoctrine()->getManager();

        // build
        $this->build($projectId, $caseStudyId);





        return $this->render('CaseStoreBundle:CaseStudyEdit:linkOutput.html.twig', array(
            'project'=>$this->project,
            'caseStudy'=>$this->caseStudy,
        ));

    }


}
