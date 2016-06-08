<?php

namespace CaseStoreBundle;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class CaseStudyFieldTypeFinderCompilerPass  implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        if (!$container->has('case_study_field_type_finder')) {
            return;
        }

        $definition = $container->findDefinition(
            'case_study_field_type_finder'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'case_study_field_type.definition'
        );
        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall(
                'addFieldType',
                array(new Reference($id))
            );
        }
    }

}
