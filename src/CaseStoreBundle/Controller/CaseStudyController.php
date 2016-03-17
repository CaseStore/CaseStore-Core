<?php

namespace CaseStoreBundle\Controller;

use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyComment;
use CaseStoreBundle\Entity\CaseStudyDocument;
use CaseStoreBundle\Entity\CaseStudyFieldValueString;
use CaseStoreBundle\Entity\CaseStudyHasLocation;
use CaseStoreBundle\Entity\CaseStudyLocation;
use CaseStoreBundle\Entity\Project;
use CaseStoreBundle\Form\Type\CaseStudyCommentNewType;
use CaseStoreBundle\Form\Type\CaseStudyDocumentNewType;
use CaseStoreBundle\Form\Type\ProjectNewType;
use CaseStoreBundle\Security\CaseStudyVoter;
use CaseStoreBundle\Security\ProjectVoter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyController extends Controller
{

    /** @var  Project */
    protected $project;

    /** @var  CaseStudy */
    protected $caseStudy;

    protected $editAccessAllowed = false;

    protected function build($projectId, $caseStudyId) {
        $doctrine = $this->getDoctrine()->getManager();
        // load
        $repository = $doctrine->getRepository('CaseStoreBundle:Project');
        $this->project = $repository->findOneByPublicId($projectId);
        if (!$this->project) {
            throw new  NotFoundHttpException('Not found');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $this->project);
        // load
        $repository = $doctrine->getRepository('CaseStoreBundle:CaseStudy');
        $this->caseStudy = $repository->findOneBy(array('project'=>$this->project, 'publicId'=>$caseStudyId));
        if (!$this->caseStudy) {
            throw new  NotFoundHttpException('Not found');
        }
        $this->denyAccessUnlessGranted(CaseStudyVoter::VIEW, $this->caseStudy);
        $this->editAccessAllowed = $this->isGranted(CaseStudyVoter::EDIT, $this->caseStudy);
    }

    public function indexAction($projectId, $caseStudyId)
    {
        // build
        $this->build($projectId, $caseStudyId);
        //data

        $doctrine = $this->getDoctrine()->getManager();
        $users =  $doctrine->getRepository('CaseStoreBundle:User')->findByCaseStudy($this->caseStudy);
        $comments =  $doctrine->getRepository('CaseStoreBundle:CaseStudyComment')->findBy(array('caseStudy'=>$this->caseStudy));
        $documents =  $doctrine->getRepository('CaseStoreBundle:CaseStudyDocument')->findBy(array('caseStudy'=>$this->caseStudy));
        $locations =  $doctrine->getRepository('CaseStoreBundle:CaseStudyLocation')->findBy(array('caseStudy'=>$this->caseStudy,'removedAt'=>null));
        $outputs =  $doctrine->getRepository('CaseStoreBundle:Output')->findByCaseStudy($this->caseStudy);
        $caseStudyFieldDefinitions = $doctrine->getRepository('CaseStoreBundle:CaseStudyFieldDefinition')->getForProject($this->project);


        $fieldValues = array();
        foreach($caseStudyFieldDefinitions as $caseStudyFieldDefinition) {

            if ($caseStudyFieldDefinition->isTypeString()) {
                $fieldValues[$caseStudyFieldDefinition->getPublicId()] =
                    $doctrine->
                    getRepository('CaseStoreBundle:CaseStudyFieldValueString')->
                    getLatestValueFor($caseStudyFieldDefinition, $this->caseStudy);
            } else if ($caseStudyFieldDefinition->isTypeText()) {
                $fieldValues[$caseStudyFieldDefinition->getPublicId()] =
                    $doctrine->
                    getRepository('CaseStoreBundle:CaseStudyFieldValueText')->
                    getLatestValueFor($caseStudyFieldDefinition, $this->caseStudy);
            } else if ($caseStudyFieldDefinition->isTypeSelect()) {
                $fieldValues[$caseStudyFieldDefinition->getPublicId()] =
                    $doctrine->
                    getRepository('CaseStoreBundle:CaseStudyFieldValueSelect')->
                    getLatestValuesFor($caseStudyFieldDefinition, $this->caseStudy);
            } else if ($caseStudyFieldDefinition->isTypeInteger()) {
                $fieldValues[$caseStudyFieldDefinition->getPublicId()] =
                    $doctrine->
                    getRepository('CaseStoreBundle:CaseStudyFieldValueInteger')->
                    getLatestValueFor($caseStudyFieldDefinition, $this->caseStudy);
            }

        }

        return $this->render('CaseStoreBundle:CaseStudy:index.html.twig', array(
            'project' => $this->project,
            'caseStudy' => $this->caseStudy,
            'editAccessAllowed'=>$this->editAccessAllowed,
            'newCommentAllowed'=>((boolean)$this->getUser()),
            'users' => $users,
            'comments' => $comments,
            'documents' => $documents,
            'locations' => $locations,
            'fieldDefinitions'=>$caseStudyFieldDefinitions,
            'fieldValues' => $fieldValues,
            'outputs' => $outputs,
        ));
    }



    public function newCommentAction($projectId, $caseStudyId)
    {
        $this->build($projectId, $caseStudyId);

        if (!$this->getUser()) {
            throw new  NotFoundHttpException('Not found');
        }

        $doctrine = $this->getDoctrine()->getManager();

        $caseStudyComment = new CaseStudyComment();
        $caseStudyComment->setCaseStudy($this->caseStudy);
        $caseStudyComment->setAddedBy($this->getUser());

        $form = $this->createForm(new CaseStudyCommentNewType(), $caseStudyComment);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $doctrine->persist($caseStudyComment);
                $doctrine->flush();
                return $this->redirect($this->generateUrl('case_store_case_study', array(
                    'projectId'=>$this->project->getPublicId(),
                    'caseStudyId'=>$this->caseStudy->getPublicId(),
                )));
            }
        }

        return $this->render('CaseStoreBundle:CaseStudy:newComment.html.twig', array(
            'project' => $this->project,
            'caseStudy' => $this->caseStudy,
            'editAccessAllowed'=>$this->editAccessAllowed,
            'form' => $form->createView(),
        ));

    }

    public function documentDownloadAction($projectId, $caseStudyId, $documentId) {
        $this->build($projectId, $caseStudyId);

        $doctrine = $this->getDoctrine()->getManager();

        $document = $doctrine->getRepository('CaseStoreBundle:CaseStudyDocument')
            ->findOneBy( array('publicId'=>$documentId, 'caseStudy'=>$this->caseStudy));
        if (!$document) {
            throw new  NotFoundHttpException('Not found');
        }


        $response = new Response(file_get_contents($document->getAbsolutePath()), 200, array('Content-Type' => $document->getMime() ));
        return $response;


    }

}
