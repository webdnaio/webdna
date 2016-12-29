<?php

namespace WebDNA\Bundle\AppBundle\Secutiry\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AnalysisVoter
 * @package WebDNA\Bundle\AppBundle\Secutiry\Voter
 */
class AnalysisVoter implements VoterInterface
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
        $supportedClass = 'WebDNA\Bundle\AppBundle\Entity\AnalysisProcess';

        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    /**
     * @param TokenInterface $token
     * @param object $analysisProcess
     * @param array $attributes
     * @return int
     */
    public function vote(TokenInterface $token, $analysisProcess, array $attributes)
    {
        // check if class of this object is supported by this voter
        if (!$this->supportsClass(get_class($analysisProcess))) {
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

        if ($attribute == self::VIEW && $user->getId() === $analysisProcess->getWebsite()->getUser()->getId()) {
            return VoterInterface::ACCESS_GRANTED;
        }

        if ($attribute == self::EDIT && $user->getId() === $analysisProcess->getWebsite()->getUser()->getId()) {
            return VoterInterface::ACCESS_GRANTED;
        }


        return VoterInterface::ACCESS_DENIED;
    }
}
