<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Student
 *
 * @ORM\Table(name="student")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StudentRepository")
 */
class Student
{
    /**
     * @var int
     *
     * @ORM\Column(name="username", type="integer")
     * @ORM\Id
     * 
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=190)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=190)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="course", type="string", length=190)
     */
    private $course;

    /**
     * @var string
     *
     * @ORM\Column(name="school", type="string", length=190)
     */
    private $school;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=190)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=190)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="postcode", type="string", length=10)
     */
    private $postcode;

    /**
     * @var int
     *
     * @ORM\Column(name="phone_no", type="integer", nullable=true)
     */
    private $phoneNo;

    /**
     * @var int
     *
     * @ORM\Column(name="mobile_no", type="integer", unique=true)
     */
    private $mobileNo;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=190, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64)
     */
    private $password;

    

    
    /**
     * @ORM\OneToMany(targetEntity="Cv", mappedBy="student")
     */
    private $cvs;

    
    /**
     * One Student has One Placement.
     * @ORM\OneToOne(targetEntity="Placement", mappedBy="student")
     */
    private $placement;
    

    /**
     * Set username
     *
     * @param integer $username
     *
     * @return Student
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return integer
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Student
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Student
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set course
     *
     * @param string $course
     *
     * @return Student
     */
    public function setCourse($course)
    {
        $this->course = $course;

        return $this;
    }

    /**
     * Get course
     *
     * @return string
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * Set school
     *
     * @param string $school
     *
     * @return Student
     */
    public function setSchool($school)
    {
        $this->school = $school;

        return $this;
    }

    /**
     * Get school
     *
     * @return string
     */
    public function getSchool()
    {
        return $this->school;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Student
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Student
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set postcode
     *
     * @param string $postcode
     *
     * @return Student
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * Get postcode
     *
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Set phoneNo
     *
     * @param integer $phoneNo
     *
     * @return Student
     */
    public function setPhoneNo($phoneNo)
    {
        $this->phoneNo = $phoneNo;

        return $this;
    }

    /**
     * Get phoneNo
     *
     * @return integer
     */
    public function getPhoneNo()
    {
        return $this->phoneNo;
    }

    /**
     * Set mobileNo
     *
     * @param integer $mobileNo
     *
     * @return Student
     */
    public function setMobileNo($mobileNo)
    {
        $this->mobileNo = $mobileNo;

        return $this;
    }

    /**
     * Get mobileNo
     *
     * @return integer
     */
    public function getMobileNo()
    {
        return $this->mobileNo;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Student
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
     * @return Student
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
     * Constructor
     */
    public function __construct()
    {
        $this->cvs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add cv
     *
     * @param \AppBundle\Entity\Cv $cv
     *
     * @return Student
     */
    public function addCv(\AppBundle\Entity\Cv $cv)
    {
        $this->cvs[] = $cv;

        return $this;
    }

    /**
     * Remove cv
     *
     * @param \AppBundle\Entity\Cv $cv
     */
    public function removeCv(\AppBundle\Entity\Cv $cv)
    {
        $this->cvs->removeElement($cv);
    }

    /**
     * Get cvs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCvs()
    {
        return $this->cvs;
    }

    /**
     * Set placement
     *
     * @param \AppBundle\Entity\Placement $placement
     *
     * @return Student
     */
    public function setPlacement(\AppBundle\Entity\Placement $placement = null)
    {
        $this->placement = $placement;

        return $this;
    }

    /**
     * Get placement
     *
     * @return \AppBundle\Entity\Placement
     */
    public function getPlacement()
    {
        return $this->placement;
    }
}
