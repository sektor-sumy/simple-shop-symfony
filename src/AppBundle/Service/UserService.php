<?php
namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Service;
use AppBundle\Exception\UserAlreadyRegisteredException;
use AppBundle\AbstractService;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

/**
 * Class UserService
 */
class UserService extends AbstractService
{
    /**
     * @var UserPasswordEncoder
     */
    protected $encoder;

    /**
     * @param TokenStorage        $tokenStorage
     * @param Registry            $doctrine
     * @param UserPasswordEncoder $encoder
     */
    public function __construct(TokenStorage $tokenStorage, Registry $doctrine, UserPasswordEncoder $encoder)
    {
        parent::__construct($tokenStorage, $doctrine);
        $this->encoder = $encoder;
    }
    /**
     * @param User $user
     * @throws UserAlreadyRegisteredException
     */
    public function register(User $user)
    {
        $email = mb_strtolower($user->getEmail());
        $isExists = $this->getDoctrine()->getRepository("AppBundle:User")->findOneBy(['email' => $email]);
        if ($isExists) {
            throw new UserAlreadyRegisteredException();
        }
        if (is_null($user->getPassword())) {
            $password = $this->generateRandomPassword();
            $user->setPassword($password);
        }
        $encodedPassword = $this->encoder->encodePassword($user, $user->getPassword());

        $user->setEmail($email);
        $user->setPassword($encodedPassword);
        $this->getEm()->persist($user);
        $this->getEm()->flush();
    }

    /**
     * @return string
     */
    public function generateRandomPassword()
    {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass);
    }

    /**
     * @param User $user
     * @throws UserAlreadyRegisteredException
     */
    public function createAdmin(User $user)
    {
        $email = mb_strtolower($user->getEmail());
        $isExists = $this->getDoctrine()->getRepository("AppBundle:User")->findOneBy(['email' => $email]);
        if ($isExists) {
            throw new UserAlreadyRegisteredException();
        }

        $password = $this->generateRandomPassword();
        $encoded = $this->encoder->encodePassword($user, $password);
        $user->setPassword($encoded);
        $user->setEmail($email);
        $this->getEm()->persist($user);
        $this->getEm()->flush();

    }
}
