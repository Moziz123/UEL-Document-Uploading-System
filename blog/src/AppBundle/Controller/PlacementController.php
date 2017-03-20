<?php

namespace AppBundle\Controller;
use AppBundle\Form\CompanyType;
use AppBundle\Form\LinemanagerType;
use AppBundle\Entity\Placement;
use AppBundle\Entity\Company;
use AppBundle\Entity\LineManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PlacementController extends Controller
{
    /**
     * @Route("/placement_confirmation", name="placement_confirmation")
     */
    public function placementConfirmationAction(Request $request)
    {  
    session_start();
    
    $_SESSION['msg'] = '';
    //Upload Job description form
    $pm = new Placement();
    //Handle displaying of job description data to the user    
    $note = array();
    
    $lineManager1 = new LineManager(); 
    $em = $this->getDoctrine()->getManager();            
    $placementForm = $this->createFormBuilder($pm)
            ->add('company', CompanyType::class)
            ->add('startDate', DateType::class)
            ->add('endDate', DateType::class) 
            ->add('jobTitle', TextType::class, array('attr' => array(
            'class' => 'form-control')))
            ->add('hoursPerWeek', TextType::class, array('attr' => array(
            'class' => 'form-control')))
            ->add('lineManager', LinemanagerType::class)                   
            ->add('submit', SubmitType::class, array('attr' => array(
            'label' => 'Submit', 'class' => 'btn btn-primary btn-lg active', 
             'style' => 'margin-top:10px')))
            ->getForm();

    //Comments form  
    $placement1 = new Placement();    
    $form = $this->createFormBuilder($placement1)
            ->add('notes', TextareaType::class, array('attr' => array(
            'class' => 'col-sm-12', 'style' => 'margin-top:30px', 'rows' => 5)))
            ->add('submit', SubmitType::class, array('attr' => array(
            'label' => 'Submit', 'class' => 'btn btn-primary btn-lg active', 
             'style' => 'margin-top:10px')))
            ->getForm();  
        
   

    $user = $_SESSION['username']; 
    $username = substr($user, 1);    
    $student = $this->getDoctrine()
               ->getRepository('AppBundle:Student')
               ->find($username);
    $placement = $student->getPlacement();

    

    $prevComs = '';
    if ($placement){
         $prevComs = $placement->getNotes();
         if($prevComs){
              $note = unserialize($prevComs);              
         }
    }
    $placementForm->handleRequest($request);

    if ($placementForm->isSubmitted() && $placementForm->isValid()) {
 
      
                
              if($placement && $placement->getStatus() == 'rejected'){
                   $placement = $placement->setLinemanager();
                   $placement = $placement->setCompany();
                   $placement = $placement->setStudent(); 
                      
                   $em->remove($placement);
                   $em->flush();
              }else{
                   $placement = new Placement();
              }
      

              $companyPostcode = $placementForm['company']['postcode']->getData();
              $company = $this->getDoctrine()
                       ->getRepository('AppBundle:Company')
                       ->findOneBy(array('postcode' => $companyPostcode));
              $linemanagerEmail = $placementForm['lineManager']['email']->getData();
              $lineManager = $this->getDoctrine()
                       ->getRepository('AppBundle:LineManager')
                       ->findOneBy(array('email' => $linemanagerEmail));
              if($company){
                       $placement->setCompany($company);
                       $em->persist($placement);
                         
              }else{
                       $company = new Company();
                       $company->setName($placementForm['company']['name']->getData());
                       $company->setAddress($placementForm['company']['address']->getData());
                       $company->setCity($placementForm['company']['city']->getData());
                       $company->setPostcode($companyPostcode);
                       
                       $placementReal = $placement->setCompany($company);
                       $em->persist($placement);  
              } 

              $startD = $placementForm['startDate']->getData()->format('Y-m-d H:i:s');
              $startDate = new \DateTime($startD);
              $endD = $placementForm['endDate']->getData()->format('Y-m-d H:i:s');
              $endDate = new \DateTime($endD);
              
              $placement->setStartDate($startDate);
              $placement->setEndDate($endDate);
              $placement->setJobTitle($placementForm['jobTitle']->getData());
              $placement->setStatus('submitted');
              $placement->setStudent($student);
              
              $placement->setHoursPerWeek($placementForm['hoursPerWeek']->getData());
              $time = new \DateTime('now'); 
              $placement->setDateUploaded($time); 
              if($lineManager){
                       $placement->setLinemanager($lineManager);
                       $em->persist($placement);
                       $em->flush();   
              }else{
                       $lineManager1->setFirstname($placementForm['lineManager']['firstname']->getData());
                       $lineManager1->setLastname($placementForm['lineManager']['lastname']->getData());
                       $lineManager1->setPosition($placementForm['lineManager']['position']->getData());
                       $lineManager1->setPhoneNo($placementForm['lineManager']['phoneNo']->getData());
                       $lineManager1->setEmail($placementForm['lineManager']['email']->getData());
                       $lineManager1->setCompany($company);
                       $em->persist($lineManager1);
                       $placement->setLinemanager($lineManager1);
                       $em->persist($placement);
                       $em->flush(); 
              
              }
        return $this->render('placement/placement_confirmation.html.twig', 
               array('form' => $form->createView(), 'placement' => $placement, 'notes' => $note, 
               'msg' => $_SESSION['msg'], 'student' => $student));
                   /*$lineManagerFirstname = $placementForm['lineManagerFirstname']->getData();
                   $lineManagerLastname = $placementForm['lineManagerLastname']->getData();
                   $lineManagerJobTitle = $placementForm['lineManagerJobTitle']->getData();
                   $lineManagerContactNumber = $placementForm['lineManagerContactNumber']->getData();
                   $lineManagerEmailAddress = $placementForm['lineManagerEmailAddress']->getData();    
                   $placement->setStartDate($startDate);
                   $placement->setEndDate($endDate);
                   $placement->setJobTitle($jobTitle);
                   $placement->setStatus('submitted');
                   $placement->setHoursPerWeek($hoursPerWeek);
              
                   die($companyName); 
                   /*$placement = $placement->setLinemanager($lm = null);
                   $placement = $placement->setCompany($c = null);
                   $placement = $placement->setStudent($s = null);                   
                   $em->persist($placement);
                   $student = $student->setPlacement($p = null);
              }else($placement->getStatus() == 'accepted'){
                   //your placement has been accepted.
                   $_SESSION['msg'] = 'Your placement has been accepted.
                   You cannot fill out another placement form at this point.';

                   return $this->render('placement/placement_confirmation.html.twig', 
                   array('form' => $form->createView(), 'placement' => $placement, 'notes' => $note, 
                   'msg' => $_SESSION['msg'], 'student' => $student));
              }else{
                   $companyName = $placementForm['company']['name']->getData();
                   die($companyName); 

              }
        }
   }   

/*
                   
                  
                   
                       
                       return $this->render('placement/placement_confirmation.html.twig', 
                       array('form' => $form->createView(), 'placement' => $placement, 'notes' => $note, 
                       'msg' => $_SESSION['msg'], 'student' => $student));  
                   }
                        
               }*/

         }  
         return $this->render('placement/placement_confirmation.html.twig', 
                       array('form' => $form->createView(), 'placementForm' => $placementForm->createView(), 'placement' => $placement, 
                       'notes' => $note, 'msg' => $_SESSION['msg'], 'student' => $student));   
         } 
          
    


    /**
     * @Route("/placement_confirmation/admin", name="admin_placement_confirmation")
     */
    public function placementConfirmationAdminAction(Request $request)
    {
        return $this->render('placement/admin_placement_confirmation.html.twig');
    }
    
    
}
