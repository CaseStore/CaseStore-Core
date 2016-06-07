<?php

namespace CaseStoreBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CaseStoreBundle\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
    * @ORM\OneToMany(targetEntity="CaseStudyHasUser", mappedBy="user")
    */
    protected $inCaseStudies;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }



    /**
     * @return mixed
     */
    public function getInCaseStudies()
    {
        return $this->inCaseStudies;
    }

    /**
     * @param mixed $inCaseStudies
     */
    public function setInCaseStudies($inCaseStudies)
    {
        $this->inCaseStudies = $inCaseStudies;
    }




}
