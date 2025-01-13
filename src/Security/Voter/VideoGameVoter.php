<?php

namespace App\Security\Voter;

use App\Model\Entity\User;
use App\Model\Entity\VideoGame;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @extends Voter<string, VideoGame>
 */
class VideoGameVoter extends Voter
{
    public const REVIEW = 'review';

    /**
     * Determines if the voter supports the given attribute and subject.
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::REVIEW === $attribute && $subject instanceof VideoGame;
    }

    /**
     * Votes on whether the user has permission for a specific action.
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return !$subject->hasAlreadyReview($user);
    }
}
