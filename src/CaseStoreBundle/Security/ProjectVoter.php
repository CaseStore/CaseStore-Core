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
        if ($attribute == self::ADMIN) {
            // Admin - only project owner can admin
            $user = $token->getUser();
            return $user && $user == $subject->getOwner();

        } else if($attribute == self::EDIT) {
            // Write - any logged in user can edit project at the moment.
            return (boolean)$token->getUser();

        } else {
            // Read - anyone can read any project at the moment, including anon.
            return true;

        }


    }

}
