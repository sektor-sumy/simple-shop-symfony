<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Table(name="public.user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements AdvancedUserInterface, \Serializable
{
    const PREFIX = 'U';
    const ROLE_ROOT = 'ROLE_ROOT';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';
    const STATUS_BANNED = 'Banned';
    const STATUS_NOT_CONFIRMED = 'Not confirmed';
    const STATUS_ACTIVE = 'Active';
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="text", unique=true)
     */
    private $email;
    /**
     * @var string
     * @ORM\Column(name="first_name", type="text", nullable=true)
     */
    private $firstName;
    /**
     * @var string
     * @ORM\Column(name="last_name", type="text", nullable=true)
     */
    private $lastName;
    /**
     * @ORM\Column(name="middle_name", type="text", nullable=true)
     */
    private $middleName;
    /**
     * @ORM\Column(type="text")
     */
    private $salt;
    /**
     * @ORM\Column(type="text")
     */
    private $password;
    /**
     * @ORM\Column(type="text")
     */
    private $role;
    /**
     * @ORM\Column(name="registered_at", type="datetime")
     */
    private $registeredAt;
    public $newPassword;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->registeredAt = new \DateTime();
        $this->salt = hash('sha256', uniqid(null, true));
        $this->role = self::ROLE_USER;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->getEmail();
    }

    /**
     * @return mixed
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return [$this->role];
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize([
            $this->id,
        ]);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list ($this->id) = unserialize($serialized);
    }

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = mb_strtolower($email);

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return true;
    }

    /**
     * @param string $role
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param \DateTime $registeredAt
     * @return User
     */
    public function setRegisteredAt($registeredAt)
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getRegisteredAt()
    {
        return $this->registeredAt;
    }

    /**
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $middleName
     * @return User
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * @return null|string
     */
    public function getArticle()
    {
        $result = null;
        if ($this->id) {
            $result = self::PREFIX.$this->id;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getStatus()
    {

        return self::STATUS_ACTIVE;
    }

    /**
     * @return bool
     */
    public function isUser()
    {
        return $this->getRole() === self::ROLE_USER;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->getRole() === self::ROLE_ADMIN;
    }

    /**
     * @return bool
     */
    public function isRoot()
    {
        return $this->getRole() === self::ROLE_ROOT;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return trim($this->firstName.' '.$this->lastName);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getArticle().' - '.$this->getFullName().' ('.$this->getEmail().')';
    }

    /**
     * @return bool
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }
}
