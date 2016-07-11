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
class LoadCodePointOpenDataCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('casestore:load-code-point-open-data')
            ->setDescription('Load Code Point Open Data')
            ->addArgument(
                'directory',
                InputArgument::REQUIRED,
                'Data Directory'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $inDir = $input->getArgument('directory');

        $outDir = __DIR__ .
            DIRECTORY_SEPARATOR. '..'.DIRECTORY_SEPARATOR. '..'.DIRECTORY_SEPARATOR. '..'.DIRECTORY_SEPARATOR .
            'ordnancesurveydata' . DIRECTORY_SEPARATOR. 'codepointopen' . DIRECTORY_SEPARATOR;

        $dataAdaptor = new \JMBTechnologyLimited\OSData\CodePointOpen\FileDataAdaptor($outDir);

        $service = new \JMBTechnologyLimited\OSData\CodePointOpen\CodePointOpenService($dataAdaptor);

        $service->loadData($inDir);

        $output->writeln("Done");

    }

}

