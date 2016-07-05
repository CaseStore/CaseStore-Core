<?php


namespace CaseStoreBundle\Command;

use CaseStoreBundle\Action\CaseStudyFieldDefinitionOptionPurgeAction;
use CaseStoreBundle\Action\CaseStudyPurgeAction;
use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\CaseStudyFieldDefinitionOption;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class PurgeCaseStudyFieldDefinitionOptionCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('casestore:purge-case-study-field-definition-option')
            ->setDescription('Purge Case Study Field Definition Option')
            ->addArgument(
                'project',
                InputArgument::REQUIRED,
                'Public ID of Project (from URL)'
            )
            ->addArgument(
                'fielddefinition',
                InputArgument::REQUIRED,
                'Public ID of Field Definition Study (from URL)'
            )
            ->addArgument(
                'option',
                InputArgument::REQUIRED,
                'Public ID of Field Definition Option Study (from page)'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $doctrine = $this->getContainer()->get('doctrine');

        ######################## Find
        $projectRepository = $doctrine->getRepository('CaseStoreBundle:Project');
        $caseStudyFieldDefinitionRepo = $doctrine->getRepository('CaseStoreBundle:CaseStudyFieldDefinition');
        $caseStudyFieldDefinitionOptionRepo = $doctrine->getRepository('CaseStoreBundle:CaseStudyFieldDefinitionOption');

        $project = $projectRepository->findOneBy(array('publicId' => $input->getArgument('project')));
        if (!$project) {
            $output->writeln('Project Not Found');
            return;
        }

        $caseStudyFieldDefinition = $caseStudyFieldDefinitionRepo->findOneBy(array('project'=>$project, 'publicId'=>$input->getArgument('fielddefinition')));
        if (!$caseStudyFieldDefinition) {
            $output->writeln('Case Study Field Definition Not Found');
            return;
        }

        $caseStudyFieldDefinitionOption = $caseStudyFieldDefinitionOptionRepo->findOneBy(array('fieldDefinition'=>$caseStudyFieldDefinition, 'publicId'=>$input->getArgument('option')));
        if (!$caseStudyFieldDefinitionOption) {
            $output->writeln('Case Study Field Definition Not Found');
            return;
        }


        ######################### User confirm
        $output->writeln('Project: '. $project->getTitle());
        $output->writeln('Case Study Field Definition: '. $caseStudyFieldDefinition->getTitle());
        $output->writeln('Case Study Field Definition Option: '. $caseStudyFieldDefinitionOption->getTitle());

        ######################### Work!
        $purgeAction = new CaseStudyFieldDefinitionOptionPurgeAction($this->getContainer());
        $purgeAction->purge($caseStudyFieldDefinitionOption);

        $output->writeln('Done');

    }

}

