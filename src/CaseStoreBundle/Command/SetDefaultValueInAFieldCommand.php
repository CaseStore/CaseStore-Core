<?php


namespace CaseStoreBundle\Command;

use CaseStoreCaseStudyFieldTypeTextBundle\Entity\CaseStudyFieldValueText;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class SetDefaultValueInAFieldCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('casestore:set-default-value-in-a-field')
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
                'value',
                InputArgument::REQUIRED,
                'Value'
            )
            ->addArgument(
                'email',
                InputArgument::REQUIRED,
                'Email'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $doctrine = $this->getContainer()->get('doctrine');

        ######################## Find
        $projectRepository = $doctrine->getRepository('CaseStoreBundle:Project');
        $caseStudyFieldDefinitionRepository = $doctrine->getRepository('CaseStoreBundle:CaseStudyFieldDefinition');
        $userRepo = $doctrine->getRepository('CaseStoreBundle:User');

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

        $value = trim($input->getArgument('value'));
        if (!$value) {
            $output->writeln('No Value');
            return;
        }

        $user = $userRepo->findOneByEmail($input->getArgument('email'));
        if (!$user) {
            $output->writeln('No User');
            return;
        }


        ######################### User confirm
        $output->writeln('Project: '. $project->getTitle() );
        $output->writeln('Field: '. $caseStudyFieldDefinition->getTitle() );
        $output->writeln('Value: '. $value );

        ######################### Work!

        $caseStudyRepository = $caseStudyFieldDefinitionRepository = $doctrine->getRepository('CaseStoreBundle:CaseStudy');

        $fieldType = $this->getContainer()->get('case_study_field_type_finder')->getFieldTypeById($caseStudyFieldDefinition->getType());


        foreach($caseStudyRepository->findByProject($project) as $caseStudy) {

            if (!$fieldType->hasAValue($caseStudyFieldDefinition, $caseStudy)) {


                if ($caseStudyFieldDefinition->isTypeText()) {
                    $valueObject = new CaseStudyFieldValueText();
                    $valueObject->setFieldDefinition($caseStudyFieldDefinition);
                    $valueObject->setCaseStudy($caseStudy);
                    $valueObject->setAddedBy($user);
                    $valueObject->setValue($value);
                    $doctrine->getManager()->persist($valueObject);
                    $doctrine->getManager()->flush();
                } else {
                    $output->writeln("We don't know how to do that field type!");

                }

            }

        }




        $output->writeln('Done');
    }
}

