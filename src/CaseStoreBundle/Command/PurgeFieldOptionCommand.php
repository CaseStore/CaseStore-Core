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
class PurgeFieldOptionCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('casestore:purge-field-command')
            ->setDescription('Purge Field Option')
            ->addArgument(
                'project',
                InputArgument::REQUIRED,
                'Public ID of Project (from URL)'
            )
            ->addArgument(
                'field',
                InputArgument::REQUIRED,
                'Public ID of Field (from URL)'
            )
            ->addArgument(
                'option',
                InputArgument::REQUIRED,
                'Public ID of Option (from URL)'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $doctrine = $this->getContainer()->get('doctrine');

        ######################## Find
        $projectRepository = $doctrine->getRepository('CaseStoreBundle:Project');
        $caseStudyFieldDefinitionRepository = $doctrine->getRepository('CaseStoreBundle:CaseStudyFieldDefinition');
        $caseStudyFieldDefinitionOptionRepository = $doctrine->getRepository('CaseStoreBundle:CaseStudyFieldDefinitionOption');

        $project = $projectRepository->findOneBy(array('publicId' => $input->getArgument('project')));
        if (!$project) {
            $output->writeln('Project Not Found');
            return;
        }

        $caseStudyFieldDefinition = $caseStudyFieldDefinitionRepository->findOneBy(array('project'=>$project, 'publicId'=>$input->getArgument('field')));
        if (!$caseStudyFieldDefinition) {
            $output->writeln('Case Study Field Definition Not Found');
            return;
        }

        $caseStudyFieldDefinitionOption = $caseStudyFieldDefinitionOptionRepository->findOneBy(array('fieldDefinition'=>$caseStudyFieldDefinition, 'publicId'=>$input->getArgument('option')));
        if (!$caseStudyFieldDefinitionOption) {
            $output->writeln('Case Study Field Definition Option Not Found');
            return;
        }

        ######################### User confirm
        $output->writeln('Project: '. $project->getTitle());
        $output->writeln('Field: '. $caseStudyFieldDefinition->getTitle());
        $output->writeln('Option: '. $caseStudyFieldDefinitionOption->getTitle());

        ######################### Work!
        $doctrine->getEntityManager()->remove($caseStudyFieldDefinitionOption);

        $caseStudyFieldValueSelectRepository = $doctrine->getRepository('CaseStoreCaseStudyFieldTypeSelectBundle:CaseStudyFieldValueSelect');
        foreach($caseStudyFieldValueSelectRepository->findBy(array('option'=>$caseStudyFieldDefinitionOption)) as $value) {
            $doctrine->getEntityManager()->remove($value);
        }

        $doctrine->getEntityManager()->flush();

        $output->writeln('Done');
    }
}

