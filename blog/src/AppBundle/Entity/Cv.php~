<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Cv
 *
 * @ORM\Table(name="cv")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CvRepository")
 */
class Cv
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
     * @ORM\Column(name="location", type="string", length=190, unique=true)
     *
     * 
     * @Assert\File(mimeTypes={ "application/pdf", "application/msword", 
     * "application/vnd.openxmlformats-officedocument.wordprocessingml.document" })
     */
    private $location;



    /**
     * @var text
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
     * @ORM\Column(name="status", type="string", length=10)
     */
    private $status;


    /**
     * @ORM\ManyToOne(targetEntity="Student", inversedBy="cvs")
     * @ORM\JoinColumn(name="student_username", referencedColumnName="username")
     */
    private $student;

    

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
     * Set location
     *
     * @param string $location
     *
     * @return Cv
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
     * @return Cv
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
     * @return Cv
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
     * @return Cv
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
     * @return Cv
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
     * Set student
     *
     * @param \AppBundle\Entity\Student $student
     *
     * @return Cv
     */
    public function setStudent(\AppBundle\Entity\Student $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return \AppBundle\Entity\Student
     */
    public function getStudent()
    {
        return $this->student;
    }
}
