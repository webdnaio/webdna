<?php

namespace WebDNA\Bundle\AppBundle\Secutiry\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class WebsiteVoter
 * @package WebDNA\Bundle\AppBundle\Secutiry\Voter
 */
class WebsiteVoter implements VoterInterface
{
    const VIEW = 'view';
    const EDIT = 'edit';

    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array(
            self::VIEW,
        ));
    }

    public function supportsClass($class)
    {
        $supportedClass = 'WebDNA\Bundle\AppBundle\Entity\Website';

        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    /**
     * @param TokenInterface $token
     * @param object $website
     * @param array $attributes
     * @return int
     */
    public function vote(TokenInterface $token, $website, array $attributes)
    {
        // check if class of this object is supported by this voter
        if (!$this->supportsClass(get_class($website))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        if (1 !== count($attributes)) {
            throw new \InvalidArgumentException(
                'Only one attribute is allowed for VIEW or EDIT'
            );
        }

        $attribute = $attributes[0];

        if (!$this->supportsAttribute($attribute)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return VoterInterface::ACCESS_DENIED;
        }

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return VoterInterface::ACCESS_GRANTED;
        }

        if ($attribute == self::VIEW && $user->getId() === $website->getUser()->getId()) {
            return VoterInterface::ACCESS_GRANTED;
        }

        if ($attribute == self::EDIT && $user->getId() === $website->getUser()->getId()) {
            return VoterInterface::ACCESS_GRANTED;
        }

        return VoterInterface::ACCESS_DENIED;
    }
}
