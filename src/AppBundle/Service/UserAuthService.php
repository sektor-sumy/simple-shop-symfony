<?php
namespace AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\ServiceTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use AppBundle\Exception\UserAuthException;

/**
 * Class UserAuthService
 */
class UserAuthService implements UserProviderInterface
{
    use ServiceTrait;

    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class)
    {
        $userRepo = $this->getDoctrine()->getRepository("AppBundle:User");

        return $userRepo->getClassName() === $class || is_subclass_of($class, $userRepo->getClassName());
    }

    /**
     * @param UserInterface $user
     * @return User
     * @throws UserAuthException
     */
    public function refreshUser(UserInterface $user)
    {
        /* @var User $user */
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', $class)
            );
        }

        try {
            $user = $this->getDoctrine()->getRepository("AppBundle:User")->find($user->getId());
        } catch (\Exception $e) {
            $this->getLogger()->error($e->getMessage(), ['exception' => $e]);
            throw new UserAuthException('Unexpected error occurred.');
        }

        return $user;
    }

    /**
     * @param string $username
     * @return User
     * @throws \Exception
     */
    public function loadUserByUsername($username)
    {
        try {
            $user = $this->getDoctrine()->getRepository("AppBundle:User")->findOneBy([
                'email' => strtolower($username),
            ]);
        } catch (\Exception $e) {
            $this->getLogger()->error($e->getMessage(), ['exception' => $e]);
            throw new UserAuthException('Unexpected error occurred.');
        }

        if (!$user) {
            $message = sprintf('Unable to find an active user identified by "%s".', $username);
            throw new UsernameNotFoundException($message);
        }

        return $user;
    }


    /**
     * @param string $apiToken
     * @return User
     * @throws \Exception
     */
    public function loadUserByApiToken($apiToken)
    {
        $user = null;
        try {
            $project = $this->getDoctrine()->getRepository("AppBundle:User")->findOneBy([
                'apiToken' => $apiToken,
            ]);
            if ($project) {
                $user = $project->getUser();
            }
        } catch (\Exception $e) {
            $this->getLogger()->error($e->getMessage(), ['exception' => $e]);
            throw new UserAuthException('Unexpected error occurred.');
        }

        return $user;
    }
}
