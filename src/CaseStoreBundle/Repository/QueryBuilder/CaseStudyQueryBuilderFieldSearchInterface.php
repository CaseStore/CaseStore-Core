<?php


namespace CaseStoreBundle\Repository\QueryBuilder;


interface CaseStudyQueryBuilderFieldSearchInterface
{


    public function getQueryBuilderJoins();
    public function getQueryBuilderWheres();
    public function getQueryBuilderParams();

}
