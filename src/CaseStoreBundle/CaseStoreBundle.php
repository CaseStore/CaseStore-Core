<?php

namespace CaseStoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;


/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStoreBundle extends Bundle
{



    static function createKey($minLength = 10, $maxLength = 100)
    {
        // This is set to make readable Ids.
        $characters = '23456789abcdefghjkmnpqrstuvwxyz';
        $string ='';
        $length = mt_rand($minLength, $maxLength);
        for ($p = 0; $p < $length; $p++)
        {
            $string .= $characters[mt_rand(0, strlen($characters)-1)];
        }
        return $string;
    }


    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new CaseStudyFieldTypeFinderCompilerPass());
    }

}
