<?php

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Company
 *
 * @ORM\Table(name="company")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CompanyRepository")
 */
class Company
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=190)
     */
    private $name;

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
     * @ORM\OneToMany(targetEntity="Placement", mappedBy="company")
     */
    private $placements;

    /**
     * @ORM\OneToMany(targetEntity="LineManager", mappedBy="company")
     */
    private $linemanagers;

    public function __construct()
    {
        $this->placements = new ArrayCollection();
        $this->linemanagers = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Company
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

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Company
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
     * @return Company
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
     * @return Company
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
     * Add placement
     *
     * @param \AppBundle\Entity\Placement $placement
     *
     * @return Company
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

    /**
     * Add linemanager
     *
     * @param \AppBundle\Entity\LineManager $linemanager
     *
     * @return Company
     */
    public function addLinemanager(\AppBundle\Entity\LineManager $linemanager)
    {
        $this->linemanagers[] = $linemanager;

        return $this;
    }

    /**
     * Remove linemanager
     *
     * @param \AppBundle\Entity\LineManager $linemanager
     */
    public function removeLinemanager(\AppBundle\Entity\LineManager $linemanager)
    {
        $this->linemanagers->removeElement($linemanager);
    }

    /**
     * Get linemanagers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLinemanagers()
    {
        return $this->linemanagers;
    }
}
