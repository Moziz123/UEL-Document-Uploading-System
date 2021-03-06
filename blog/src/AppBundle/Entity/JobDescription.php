<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Placement;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * JobDescription
 *
 * @ORM\Table(name="job_description")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\JobDescriptionRepository")
 */
class JobDescription
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
     * @ORM\Column(name="location", type="string", length=190, unique=true)
     */
    private $location;

    /**
     * @var string
     * @Assert\Length(
     * min = 0)
     * @ORM\Column(name="notes", type="text", nullable=true)
     */
    private $notes;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_uploaded", type="datetime")
     */
    private $dateUploaded;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_modified", type="datetime")
     */
    private $lastModified;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=190)
     */
    private $status;

    /**
     * One JobDescription is from One Placement.
     * @ORM\OneToOne(targetEntity="Placement")
     * @ORM\JoinColumn(name="placement_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $placement;
    

    

   
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
     * Set location
     *
     * @param string $location
     *
     * @return JobDescription
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set notes
     *
     * @param string $notes
     *
     * @return JobDescription
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set dateUploaded
     *
     * @param \DateTime $dateUploaded
     *
     * @return JobDescription
     */
    public function setDateUploaded($dateUploaded)
    {
        $this->dateUploaded = $dateUploaded;

        return $this;
    }

    /**
     * Get dateUploaded
     *
     * @return \DateTime
     */
    public function getDateUploaded()
    {
        return $this->dateUploaded;
    }

    /**
     * Set lastModified
     *
     * @param \DateTime $lastModified
     *
     * @return JobDescription
     */
    public function setLastModified($lastModified)
    {
        $this->lastModified = $lastModified;

        return $this;
    }

    /**
     * Get lastModified
     *
     * @return \DateTime
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return JobDescription
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    

 
    /**
     * Set placement
     *
     * @param \AppBundle\Entity\Placement $placement
     *
     * @return JobDescription
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
