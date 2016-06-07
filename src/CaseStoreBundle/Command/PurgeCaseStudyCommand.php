<?php


namespace CaseStoreBundle\Command;

use CaseStoreBundle\Action\CaseStudyPurgeAction;
use CaseStoreBundle\Entity\CaseStudy;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class PurgeCaseStudyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('casestore:purge-case-study')
            ->setDescription('Purge Case Study')
            ->addArgument(
                'project',
                InputArgument::REQUIRED,
                'Public ID of Project (from URL)'
            )
            ->addArgument(
                'casestudy',
                InputArgument::REQUIRED,
                'Public ID of Case Study (from URL)'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $doctrine = $this->getContainer()->get('doctrine');

        ######################## Find
        $projectRepository = $doctrine->getRepository('CaseStoreBundle:Project');
        $caseStudyRepo = $doctrine->getRepository('CaseStoreBundle:CaseStudy');

        $project = $projectRepository->findOneBy(array('publicId' => $input->getArgument('project')));
        if (!$project) {
            $output->writeln('Project Not Found');
            return;
        }

        $caseStudy = $caseStudyRepo->findOneBy(array('project'=>$project, 'publicId'=>$input->getArgument('casestudy')));
        if (!$caseStudy) {
            $output->writeln('Case Study Not Found');
            return;
        }

        ######################### User confirm
        $output->writeln('Project: '. $project->getTitle());
        $output->writeln('Case Study: '. $caseStudy->getPublicId());

        ######################### Work!
        $purgeAction = new CaseStudyPurgeAction($this->getContainer());
        $purgeAction->purge($caseStudy);

        $output->writeln('Done');

    }

}

