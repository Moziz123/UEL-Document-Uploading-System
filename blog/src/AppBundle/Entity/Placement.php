<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\JobDescription;
use AppBundle\Entity\Student;

/**
 * Placement
 *
 * @ORM\Table(name="placement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PlacementRepository")
 */
class Placement
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
     * @ORM\Column(name="job_title", type="string", length=190)
     */
    private $jobTitle;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="datetime")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="datetime")
     */
    private $endDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateUploaded", type="datetime")
     */
    private $dateUploaded;

    /**
     * @var string
     *
     * @ORM\Column(name="hoursPerWeek", type="string", length=30, nullable=true)
     */
    private $hoursPerWeek;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=30)
     */
    private $status;


    /**
     * One Placement is from One Student.
     * @ORM\OneToOne(targetEntity="Student", inversedBy="placement")
     * @ORM\JoinColumn(name="student_username", referencedColumnName="username")
     */
    private $student;

   
    /**
     * @ORM\ManyToOne(targetEntity="Company", inversedBy="placements")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
     */
    private $company;
    
    
   /**
     * @ORM\ManyToOne(targetEntity="LineManager", inversedBy="placements")
     * @ORM\JoinColumn(name="linemanager_username", referencedColumnName="username")
     */
    private $linemanager_username; 
     
    


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
     * Set jobTitle
     *
     * @param string $jobTitle
     *
     * @return Placement
     */
    public function setJobTitle($jobTitle)
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    /**
     * Get jobTitle
     *
     * @return string
     */
    public function getJobTitle()
    {
        return $this->jobTitle;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Placement
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Placement
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set hoursPerWeek
     *
     * @param string $hoursPerWeek
     *
     * @return Placement
     */
    public function setHoursPerWeek($hoursPerWeek)
    {
        $this->hoursPerWeek = $hoursPerWeek;

        return $this;
    }

    /**
     * Get hoursPerWeek
     *
     * @return string
     */
    public function getHoursPerWeek()
    {
        return $this->hoursPerWeek;
    }

   

  
  

    /**
     * Set company
     *
     * @param \AppBundle\Entity\Company $company
     *
     * @return Placement
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
     * Set linemanagerUsername
     *
     * @param \AppBundle\Entity\LineManager $linemanagerUsername
     *
     * @return Placement
     */
    public function setLinemanagerUsername(\AppBundle\Entity\LineManager $linemanagerUsername = null)
    {
        $this->linemanager_username = $linemanagerUsername;

        return $this;
    }

    /**
     * Get linemanagerUsername
     *
     * @return \AppBundle\Entity\LineManager
     */
    public function getLinemanagerUsername()
    {
        return $this->linemanager_username;
    }

    /**
     * Set student
     *
     * @param \AppBundle\Entity\Student $student
     *
     * @return Placement
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

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Placement
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
     * Set dateUploaded
     *
     * @param \DateTime $dateUploaded
     *
     * @return Placement
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
}
