<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmployerRiskAssessment
 *
 * @ORM\Table(name="employer_risk_assessment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EmployerRiskAssessmentRepository")
 */
class EmployerRiskAssessment
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
     *
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
     * @ORM\Column(name="status", type="string", length=30)
     */
    private $status;

    /**
     * One Employer Risk Assesment is from One Placement.
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
     * Set notes
     *
     * @param string $notes
     *
     * @return EmployerRiskAssessment
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
     * @return EmployerRiskAssessment
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
     * @return EmployerRiskAssessment
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
     * @return EmployerRiskAssessment
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
     * Set location
     *
     * @param string $location
     *
     * @return EmployerRiskAssessment
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
     * Set placement
     *
     * @param \AppBundle\Entity\Placement $placement
     *
     * @return EmployerRiskAssessment
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
