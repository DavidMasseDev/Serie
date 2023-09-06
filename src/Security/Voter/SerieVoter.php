<?php

namespace App\Security\Voter;

use App\Entity\Serie;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class SerieVoter extends Voter
{
    public const DELETE = 'SERIE_DELETE';
//    public const VIEW = 'POST_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::DELETE])
            && $subject instanceof \App\Entity\Serie;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        return match ($attribute) {
            self::DELETE => $this->canDelete($subject, $user),
            default => false,
        };

        // Le swicth et le match sont équivalents.
//        switch ($attribute) {
//            case self::DELETE:
//
//                return $this->canDelete($subject, $user);
//                break;
//
//        }
//
//        return false;

    }

    private function canDelete(mixed $subject, UserInterface $user): bool
    {
        if(in_array("ROLE_ADMIN", $user->getRoles())){
            return true;
        }
        /**
         * @var Serie $subject
         */
        if($subject->getStatus() == 'ended'){
            return true;
        }

        return false;
    }
}
