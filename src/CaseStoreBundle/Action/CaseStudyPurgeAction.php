<?php


namespace CaseStoreBundle\Action;
use CaseStoreBundle\Entity\CaseStudy;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyPurgeAction
{

    protected $app;

    /**
     * PurgeCaseStudyAction constructor.
     * @param $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    public function purge(CaseStudy $caseStudy) {

        $doctrine = $this->app->get('doctrine');

        $doctrine->getEntityManager()->remove($caseStudy);

        foreach(array('CaseStoreCaseStudyFieldTypeSelectBundle:CaseStudyFieldValueSelectCache',
                    'CaseStoreCaseStudyFieldTypeSelectBundle:CaseStudyFieldValueSelect',
                    'CaseStoreCaseStudyFieldTypeIntegerBundle:CaseStudyFieldValueInteger',
                    'CaseStoreCaseStudyFieldTypeStringBundle:CaseStudyFieldValueStringCache',
                    'CaseStoreCaseStudyFieldTypeStringBundle:CaseStudyFieldValueString',
                    'CaseStoreCaseStudyFieldTypeTextBundle:CaseStudyFieldValueTextCache',
                    'CaseStoreCaseStudyFieldTypeTextBundle:CaseStudyFieldValueText',
                    'CaseStoreBundle:CaseStudyHasUser',
                    'CaseStoreBundle:CaseStudyHasOutput',
                    'CaseStoreBundle:CaseStudyLocation') as $repoString) {
            foreach ($doctrine->getRepository($repoString)->findBy(array('caseStudy' => $caseStudy)) as $value) {
                $doctrine->getEntityManager()->remove($value);
            }
        }

        $documentFileNames = array();

        foreach ($doctrine->getRepository('CaseStoreBundle:CaseStudyDocument')->findBy(array('caseStudy' => $caseStudy)) as $document) {
            $doctrine->getEntityManager()->remove($document);
            $documentFileNames[] = $document->getAbsolutePath();
        }

        $doctrine->getEntityManager()->flush();

        // Now we know DB entries deleted: delete files. Decided it was better to do it in this order.
        // Files with no DB entries = bit of wasted storage space, could check and clear up later.
        // DB entries with no files = broken data in system.
        // This way, if error while deleting DB entries, system is left in stable state, not broken state.
        foreach($documentFileNames as $documentFileName) {
            unlink($documentFileName);
        }

    }


}
