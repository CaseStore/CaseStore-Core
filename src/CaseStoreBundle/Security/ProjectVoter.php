<?php

namespace CaseStoreBundle\Security;

use CaseStoreBundle\Entity\Project;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;



/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class ProjectVoter extends Voter
{

    const VIEW = 'view';
    const EDIT = 'edit';
    const ADMIN = 'admin';

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW, self::EDIT, self::ADMIN))) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof Project) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        return true;
    }

}
