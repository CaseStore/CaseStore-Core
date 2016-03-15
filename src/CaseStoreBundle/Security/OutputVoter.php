<?php

namespace CaseStoreBundle\Security;

use CaseStoreBundle\Entity\CaseStudy;
use CaseStoreBundle\Entity\Output;
use CaseStoreBundle\Entity\Project;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;



/**
 *  @license 3-clause BSD
 *  @link https://github.com/CaseStore/CaseStore-Core
 */
class OutputVoter extends Voter
{

    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::VIEW, self::EDIT))) {
            return false;
        }

        if (!$subject instanceof Output) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {

        $user = $token->getUser();

        if ($attribute == self::EDIT && !($user instanceof \CaseStoreBundle\Entity\User)) {
            return false;
        }


        return true;
    }

}
