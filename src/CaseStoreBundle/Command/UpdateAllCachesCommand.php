<?php


namespace CaseStoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class UpdateAllCachesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('casestore:update-all-caches')
            ->setDescription('Update All Caches')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $commandOutput)
    {

        $doctrine = $this->getContainer()->get('doctrine');

        $caseStudyRepository = $doctrine->getRepository('CaseStoreBundle:CaseStudy');

        foreach($caseStudyRepository->findAll() as $caseStudy) {
            $caseStudyRepository->updateCaches($caseStudy);
        }


        $outputRepository = $doctrine->getRepository('CaseStoreBundle:Output');

        foreach($outputRepository->findAll() as $output) {
            $outputRepository->updateCaches($output);
        }


        $projectRepository = $doctrine->getRepository('CaseStoreBundle:Project');

        foreach($projectRepository->findAll() as $project) {
            foreach($doctrine->getRepository('CaseStoreBundle:CaseStudyFieldDefinition')->getForProject($project) as $fieldDefinition) {
                $this->getContainer()->get('case_study_field_type_finder')->getFieldTypeById($fieldDefinition->getType())->updateCaches($fieldDefinition);
            }
        }

        $commandOutput->writeln('Done');
    }
}

