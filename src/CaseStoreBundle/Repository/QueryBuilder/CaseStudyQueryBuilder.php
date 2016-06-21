<?php


namespace CaseStoreBundle\Repository\QueryBuilder;

use CaseStoreBundle\Entity\Output;
use CaseStoreBundle\Entity\Project;
use CaseStoreBundle\Entity\User;

class CaseStudyQueryBuilder {

    protected $em;

    /** @var  Project */
    protected $project;

    /**
     * CaseStudyQueryBuilder constructor.
     * @param $em
     * @param Project $project
     */
    public function __construct($em, Project $project)
    {
        $this->em = $em;
        $this->project = $project;
    }

    /** @var  User */
    protected $userInProject;

    /**
     * @return User
     */
    public function getUserInProject()
    {
        return $this->userInProject;
    }

    /**
     * @param User $userInProject
     */
    public function setUserInProject($userInProject)
    {
        $this->userInProject = $userInProject;
    }

    /** @var Output */
    protected $output;

    /**
     * @return Output
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param Output $output
     */
    public function setOutput($output)
    {
        $this->output = $output;
    }


    protected $fieldSearches = array();

    public function addFieldSearch(CaseStudyQueryBuilderFieldSearchInterface $fieldSearch = null) {
        if (!is_null($fieldSearch)) {
            $this->fieldSearches[] = $fieldSearch;
        }
    }


    public function getQuery() {

        $joins = array();
        $where = array("cs.project = :project");
        $params = array("project"=> $this->project);

        if ($this->userInProject) {
            $joins[] = " JOIN cs.hasUsers cshu ";
            $where[] = " cshu.user = :user AND cshu.removedAt IS NULL ";
            $params['user'] = $this->userInProject;
        }

        if ($this->output) {
            $joins[] = " JOIN cs.hasOutputs csho ";
            $where[] = " csho.output = :output AND csho.removedAt IS NULL ";
            $params['output'] = $this->output;
        }

        foreach($this->fieldSearches as $fieldSearch) {
            $joins = array_merge($joins, $fieldSearch->getQueryBuilderJoins());
            $where = array_merge($where, $fieldSearch->getQueryBuilderWheres());
            foreach($fieldSearch->getQueryBuilderParams() as $k => $v) {
                $params[$k] = $v;
            }
        }

        $query =  $s =  $this->em
            ->createQuery(
                ' SELECT cs FROM CaseStoreBundle:CaseStudy cs'.
                join(' ', $joins).
                ' WHERE '. join(" AND ", $where)
            )
            ->setParameters($params);

        return $query;
    }


}

