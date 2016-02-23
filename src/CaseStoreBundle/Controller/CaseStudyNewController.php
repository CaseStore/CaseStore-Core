<?php

namespace CaseStoreBundle\Controller;

use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyHasUser;
use CaseStoreBundle\Entity\Project;
use CaseStoreBundle\Form\Type\CaseStudyNewType;
use CaseStoreBundle\Form\Type\ProjectNewType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyNewController extends ProjectController
{

    public function indexAction($projectId)
    {
        $this->build($projectId);

        $doctrine = $this->getDoctrine()->getManager();

        $casestudy = new CaseStudy();
        $casestudy->setProject($this->project);

        $form = $this->createForm(new CaseStudyNewType(), $casestudy);
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $doctrine->persist($casestudy);
                $caseStudyHasUser = new CaseStudyHasUser();
                $caseStudyHasUser->setUser($this->getUser());
                $caseStudyHasUser->setAddedBy($this->getUser());
                $caseStudyHasUser->setCaseStudy($casestudy);
                $doctrine->persist($caseStudyHasUser);
                $doctrine->flush();
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
