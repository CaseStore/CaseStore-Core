<?php

namespace CaseStoreBundle\Security;

use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\Project;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;



/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class CaseStudyVoter extends Voter
{

    const VIEW = 'view';
    const EDIT = 'edit';


    /** @var  EntityManager */
    protected $entityManager;

    /**
     * CaseStudyVoter constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::VIEW, self::EDIT))) {
            return false;
        }

        if (!$subject instanceof CaseStudy) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {

        $user = $token->getUser();

        if ($attribute == self::EDIT) {

            // Anon users def can't
            if (!($user instanceof \CaseStoreBundle\Entity\User)) {
                return false;
            }

            // Project Owners can edit all!
            if ($subject->getProject()->getOwner() == $user) {
                return true;
            }

            // Staff involved in case study can edit
            if ($this->entityManager->getRepository('CaseStoreBundle:CaseStudyHasUser')->isCaseStudyHaveUser($subject, $user)) {
                return true;
            }

            // No edit ....
            return false;


        } else if ($attribute == self::VIEW) {

            // Everyone can view
            return true;

        }
    }

}
