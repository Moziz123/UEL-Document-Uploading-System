<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LineManager
 *
 * @ORM\Table(name="line_manager")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LineManagerRepository")
 */
class LineManager
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
     * @ORM\Column(name="position", type="string", length=190)
     */
    private $position;

    /**
     * @var int
     *
     * @ORM\Column(name="phone_no", type="integer", unique=true)
     */
    private $phoneNo;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=190, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64, nullable=true)
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="linemanagers")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $company;

    /**
     * @ORM\OneToMany(targetEntity="Placement", mappedBy="linemanager")
     */
    private $placements;

    public function __construct()
    {
        $this->placements = new ArrayCollection();
    }



    

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return LineManager
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
     * @return LineManager
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
     * Set position
     *
     * @param string $position
     *
     * @return LineManager
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set phoneNo
     *
     * @param integer $phoneNo
     *
     * @return LineManager
     */
    public function setPhoneNo($phoneNo)
    {
        $this->phoneNo = $phoneNo;

        return $this;
    }

    /**
     * Get phoneNo
     *
     * @return int
     */
    public function getPhoneNo()
    {
        return $this->phoneNo;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return LineManager
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
     * Set username
     *
     * @param integer $username
     *
     * @return LineManager
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
     * Set password
     *
     * @param string $password
     *
     * @return LineManager
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
     * Set company
     *
     * @param \AppBundle\Entity\Company $company
     *
     * @return LineManager
     */
    public function setCompany(\AppBundle\Entity\Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \AppBundle\Entity\Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Add placement
     *
     * @param \AppBundle\Entity\Placement $placement
     *
     * @return LineManager
     */
    public function addPlacement(\AppBundle\Entity\Placement $placement)
    {
        $this->placements[] = $placement;

        return $this;
    }

    /**
     * Remove placement
     *
     * @param \AppBundle\Entity\Placement $placement
     */
    public function removePlacement(\AppBundle\Entity\Placement $placement)
    {
        $this->placements->removeElement($placement);
    }

    /**
     * Get placements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlacements()
    {
        return $this->placements;
    }
}
