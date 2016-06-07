<?php


namespace CaseStoreBundle\Repository\QueryBuilder;

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

    public function getQuery() {

        $joins = array();
        $where = array("cs.project = :project");
        $params = array("project"=> $this->project);

        if ($this->userInProject) {
            $joins[] = " JOIN cs.hasUsers cshu ";
            $where[] = " cshu.user = :user AND cshu.removedAt IS NULL  ";
            $params['user'] = $this->userInProject;
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

