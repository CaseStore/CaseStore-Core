<?php

namespace CaseStoreBundle\Controller;

use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyComment;
use CaseStoreBundle\Entity\CaseStudyHasLocation;
use CaseStoreBundle\Entity\Project;
use CaseStoreBundle\Form\Type\CaseStudyCommentNewType;
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
class DataController extends Controller
{

    public function postcodeAction($postcode)
    {

        $outDir = __DIR__ .
            DIRECTORY_SEPARATOR. '..'.DIRECTORY_SEPARATOR. '..'.DIRECTORY_SEPARATOR. '..'.DIRECTORY_SEPARATOR .
            'ordnancesurveydata' . DIRECTORY_SEPARATOR. 'codepointopen' . DIRECTORY_SEPARATOR;

        $dataAdaptor = new \JMBTechnologyLimited\OSData\CodePointOpen\FileDataAdaptor($outDir);

        $service = new \JMBTechnologyLimited\OSData\CodePointOpen\CodePointOpenService($dataAdaptor);

        $postcode = $service->getPostcode($postcode);

        if ($postcode) {
            $response = new Response(json_encode(array(
                'result' => true,
                'lat' => $postcode->getLat(),
                'lng' => $postcode->getLng(),
            )));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        } else {
            $response = new Response(json_encode(array(
                'result' => false,
            )));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }


    }

}
