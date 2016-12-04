<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as AssertBridge;

/**
 * Users.
 *
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\UsersRepository")
 * @AssertBridge\UniqueEntity(
 *     fields="email",
 *     errorPath="not valid",
 *     message="This email is already in use."
 * )
 * @AssertBridge\UniqueEntity(
 *     fields="username",
 *     errorPath="not valid",
 *     message="This username is already in use."
 * )
 */
class Users implements UserInterface
{
    const ROLE_DEFAULT    = 'ROLE_USER';
    
    use Timestampable;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var array
     *
     * @ORM\Column(name="role", type="array", length=25, nullable=false)
     */
    protected $roles = [self::ROLE_DEFAULT];

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", nullable=false)
     * @Gedmo\Slug(fields={"firstName", "lastName"}, separator="-", updatable=true)
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=80, nullable=true)
     */
    protected $password;

    /**
     * @Assert\NotBlank(message="plainPassword required true")
     * @Assert\Length(max=4096)
     */
    protected $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="firstName required true")
     * @Assert\Length(min=2, max=255)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="lastName required true")
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255,  unique=true, nullable=true)
     * @Assert\NotBlank(message="email required true")
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="score", type="integer", nullable=true)
     */
    protected $score;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_learn", type="datetime", nullable=true)
     */
    protected $startLearn;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_learn", type="datetime", nullable=true)
     */
    protected $endLearn;

    /**
     * @var string
     *
     * @ORM\Column(name="second_sum", type="integer", nullable=true)
     */
    protected $secondSum;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $name;


    //----------------------End additional Method for Role----------------------

    /**
     * Returns the user roles.
     *
     * @return array The roles
     */
    public function getRoles()
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    /**
     * Never use this to check if this user has access to anything!
     *
     * Use the SecurityContext, or an implementation of AccessDecisionManager
     * instead, e.g.
     *
     *     $securityContext->isGranted('ROLE_USER');
     *
     * @param string $role
     *
     * @return bool
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    public function setRoles(array $roles)
    {
        $this->roles = array();

        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    /**
     * @param string $role
     * @return $this
     */
    public function addRole($role)
    {
        $role = strtoupper($role);
        if ($role === static::ROLE_DEFAULT) {
            return $this;
        }

        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole($role)
    {
        if (count($this->roles) > 1 && false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    //----------------------Method for Userinterface----------------------

    /**
     * Get salt.
     *
     * @return string
     */
    public function getSalt()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }

    //----------------------End method for Userinterface----------------------

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Users
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Users
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return Users
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return Users
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Users
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }    

    /**
     * Set score
     *
     * @param integer $score
     * @return Users
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer 
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set startLearn
     *
     * @param \DateTime $startLearn
     * @return Users
     */
    public function setStartLearn($startLearn)
    {
        $this->startLearn = $startLearn;

        return $this;
    }

    /**
     * Get startLearn
     *
     * @return \DateTime 
     */
    public function getStartLearn()
    {
        return $this->startLearn;
    }

    /**
     * Set endLearn
     *
     * @param \DateTime $endLearn
     * @return Users
     */
    public function setEndLearn($endLearn)
    {
        $this->endLearn = $endLearn;

        return $this;
    }

    /**
     * Get endLearn
     *
     * @return \DateTime 
     */
    public function getEndLearn()
    {
        return $this->endLearn;
    }

    /**
     * Set secondSum
     *
     * @param integer $secondSum
     * @return Users
     */
    public function setSecondSum($secondSum)
    {
        $this->secondSum = $secondSum;

        return $this;
    }

    /**
     * Get secondSum
     *
     * @return integer 
     */
    public function getSecondSum()
    {
        return $this->secondSum;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Users
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
}
