<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=255, nullable=false)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="firstnameUser", type="string", length=255, nullable=true)
     */
    private $firstnameuser;

    /**
     * @var string
     *
     * @ORM\Column(name="lastnameUser", type="string", length=255, nullable=true)
     */
    private $lastnameuser;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var integer
     *
     * @ORM\Column(name="rankUser", type="integer", nullable=false)
     */
    private $rankuser;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isActive", type="boolean", nullable=false)
     */
    private $isactive = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="lastLoginDate", type="decimal", precision=11, scale=0, nullable=true)
     */
    private $lastlogindate;



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
     * Set login
     *
     * @param string $login
     *
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set firstnameuser
     *
     * @param string $firstnameuser
     *
     * @return User
     */
    public function setFirstnameuser($firstnameuser)
    {
        $this->firstnameuser = $firstnameuser;

        return $this;
    }

    /**
     * Get firstnameuser
     *
     * @return string
     */
    public function getFirstnameuser()
    {
        return $this->firstnameuser;
    }

    /**
     * Set lastnameuser
     *
     * @param string $lastnameuser
     *
     * @return User
     */
    public function setLastnameuser($lastnameuser)
    {
        $this->lastnameuser = $lastnameuser;

        return $this;
    }

    /**
     * Get lastnameuser
     *
     * @return string
     */
    public function getLastnameuser()
    {
        return $this->lastnameuser;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
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

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
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
     * Set rankuser
     *
     * @param integer $rankuser
     *
     * @return User
     */
    public function setRankuser($rankuser)
    {
        $this->rankuser = $rankuser;

        return $this;
    }

    /**
     * Get rankuser
     *
     * @return integer
     */
    public function getRankuser()
    {
        return $this->rankuser;
    }

    /**
     * Set isactive
     *
     * @param boolean $isactive
     *
     * @return User
     */
    public function setIsactive($isactive)
    {
        $this->isactive = $isactive;

        return $this;
    }

    /**
     * Get isactive
     *
     * @return boolean
     */
    public function getIsactive()
    {
        return $this->isactive;
    }

    /**
     * Set lastlogindate
     *
     * @param string $lastlogindate
     *
     * @return User
     */
    public function setLastlogindate($lastlogindate)
    {
        $this->lastlogindate = $lastlogindate;

        return $this;
    }

    /**
     * Get lastlogindate
     *
     * @return string
     */
    public function getLastlogindate()
    {
        return $this->lastlogindate;
    }
}
