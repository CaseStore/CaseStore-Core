<?php

namespace CaseStoreBundle\Controller;

use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyComment;
use CaseStoreBundle\Entity\CaseStudyDocument;
use CaseStoreBundle\Entity\CaseStudyFieldValueString;
use CaseStoreBundle\Entity\CaseStudyHasLocation;
use CaseStoreBundle\Entity\CaseStudyLocation;
use CaseStoreBundle\Entity\Output;
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
class OutputController extends Controller
{

    /** @var  Project */
    protected $project;

    /** @var  Output */
    protected $output;

    protected $editAccessAllowed = false;

    protected function build($projectId, $outputId) {
        $doctrine = $this->getDoctrine()->getManager();
        // load
        $repository = $doctrine->getRepository('CaseStoreBundle:Project');
        $this->project = $repository->findOneByPublicId($projectId);
        if (!$this->project) {
            throw new  NotFoundHttpException('Not found');
        }
        $this->denyAccessUnlessGranted(ProjectVoter::VIEW, $this->project);
        // load
        $repository = $doctrine->getRepository('CaseStoreBundle:Output');
        $this->output = $repository->findOneBy(array('project'=>$this->project, 'publicId'=>$outputId));
        if (!$this->output) {
            throw new  NotFoundHttpException('Not found');
        }
        $this->denyAccessUnlessGranted(CaseStudyVoter::VIEW, $this->output);
        $this->editAccessAllowed = $this->isGranted(CaseStudyVoter::EDIT, $this->output);
    }

    public function indexAction($projectId, $outputId)
    {
        // build
        $this->build($projectId, $outputId);
        //data

        $doctrine = $this->getDoctrine()->getManager();
        $outputFieldDefinitions = $doctrine->getRepository('CaseStoreBundle:OutputFieldDefinition')->getForProject($this->project);
        $caseStudies =  $doctrine->getRepository('CaseStoreBundle:CaseStudy')->findByOutput($this->output);
        $documents =  $doctrine->getRepository('CaseStoreBundle:OutputDocument')->findBy(array('output'=>$this->output));


        $fieldValues = array();
        foreach($outputFieldDefinitions as $outputFieldDefinition) {

            if ($outputFieldDefinition->isTypeString()) {
                $fieldValues[$outputFieldDefinition->getPublicId()] =
                    $doctrine->
                    getRepository('CaseStoreBundle:OutputFieldValueString')->
                    getLatestValueFor($outputFieldDefinition, $this->output);
            } else if ($outputFieldDefinition->isTypeText()) {
                $fieldValues[$outputFieldDefinition->getPublicId()] =
                    $doctrine->
                    getRepository('CaseStoreBundle:OutputFieldValueText')->
                    getLatestValueFor($outputFieldDefinition, $this->output);
            }

        }

        return $this->render('CaseStoreBundle:Output:index.html.twig', array(
            'project' => $this->project,
            'output' => $this->output,
            'editAccessAllowed'=>$this->editAccessAllowed,
            'fieldDefinitions'=>$outputFieldDefinitions,
            'fieldValues' => $fieldValues,
            'caseStudies' => $caseStudies,
            'documents' => $documents,
        ));
    }

    public function documentDownloadAction($projectId, $outputId, $documentId) {
        $this->build($projectId, $outputId);

        $doctrine = $this->getDoctrine()->getManager();

        $document = $doctrine->getRepository('CaseStoreBundle:OutputDocument')
            ->findOneBy( array('publicId'=>$documentId, 'output'=>$this->output));
        if (!$document) {
            throw new  NotFoundHttpException('Not found');
        }


        $response = new Response(file_get_contents($document->getAbsolutePath()), 200, array('Content-Type' => $document->getMime() ));
        return $response;


    }

}
