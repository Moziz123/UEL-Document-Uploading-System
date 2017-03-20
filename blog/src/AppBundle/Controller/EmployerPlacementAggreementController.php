<?php

namespace AppBundle\Controller;

use AppBundle\Entity\EmployerPlacementAggreement;
use AppBundle\Entity\Placement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use AppBundle\Form\EmployerPlacementAggreementType;
class EmployerPlacementAggreementController extends Controller
{
    
      /**
     * @Route("/emp_placement_aggreement", name="emp_placement_aggreement")
     */
    public function empPlacementAggreementAction(Request $request)
    {
    session_start();
    
    $_SESSION['msg'] = '';
    //Upload Job description form
    $empPlacAgg1 = new EmployerPlacementAggreement();
    //Handle displaying of job description data to the user    
    $note = array();

    $em = $this->getDoctrine()->getManager();            
    $empPlacAggForm = $this->createForm(EmployerPlacementAggreementType::class, $empPlacAgg1)            
            ->add('submit', SubmitType::class, array('attr' => array(
            'label' => 'Upload', 'class' => 'btn btn-primary btn-lg active', 
             'style' => 'margin-top:10px')));

    //Comments form  
    $empPlacAgg = new EmployerPlacementAggreement();    
    $form = $this->createFormBuilder($empPlacAgg)
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

    $jobDescription = '';

    $latestEmpPlacAgg = '';
    $latestTime = '';
    $prevComs = '';
    if ($placement){
    $jobDescription = $this->getDoctrine()
               ->getRepository('AppBundle:JobDescription')
               ->findOneBy(array('placement' => $placement->getId()));
    
    $latestEmpPlacAgg = $this->getDoctrine()
               ->getRepository('AppBundle:EmployerPlacementAggreement')
               ->findOneBy(array('placement' => $placement->getId()));
    }
    if($latestEmpPlacAgg){
         $prevComs = $latestEmpPlacAgg->getNotes();
         if($prevComs){
              $note = unserialize($prevComs);              
         }
    }         
    


    //Handle uploading of employer placement aggreement 
    $empPlacAggForm->handleRequest($request);
    if ($empPlacAggForm->isSubmitted() && $empPlacAggForm->isValid()) {
 
        if($jobDescription){  
                
              if($jobDescription->getStatus() == 'uploaded'){

                   //you need to wait for approval before uploading a employer placement aggreement 
                   $_SESSION['msg'] = 'Your job description needs to be approved before 
                   uploading an employer placement aggreement document.';

                   return $this->render('emp_placement_aggreement/emp_placement_aggreement.html.twig', 
                   array('form' => $form->createView(), 'empPlacAggForm' => $empPlacAggForm->createView(),
                   'empPlacAgg' => $latestEmpPlacAgg, 'notes' => $note, 'msg' => $_SESSION['msg'], 'username' => $user));  
              }elseif($jobDescription->getStatus() == 'accepted'){
                  
                   if($latestEmpPlacAgg){
                         //do some checks on job description
                       
                        if($latestEmpPlacAgg->getStatus() == 'uploaded'){
                             
                             //you need to wait for approval before uploading an employer placement aggreement
                             $_SESSION['msg'] = 'There is an Employer placement aggreement document currently uploaded. 
                             Please wait for a decision before uploading another Employer placement aggreement document.';

                             return $this->render('emp_placement_aggreement/emp_placement_aggreement.html.twig', 
                             array('form' => $form->createView(), 'empPlacAggForm' => $empPlacAggForm->createView(),
                            'empPlacAgg' => $latestEmpPlacAgg, 'notes' => $note, 'msg' => $_SESSION['msg'], 'username' => $user));   

                        }elseif($latestEmpPlacAgg->getStatus() == 'accepted'){
                             $_SESSION['msg'] = 'Your Employer placement aggreement has been accepted.  You cannot upload another document.';

                             return $this->render('emp_placement_aggreement/emp_placement_aggreement.html.twig', 
                             array('form' => $form->createView(), 'empPlacAggForm' => $empPlacAggForm->createView(),
                            'empPlacAgg' => $latestEmpPlacAgg, 'notes' => $note, 'msg' => $_SESSION['msg'], 'username' => $user));  
                        }else{
                             $_SESSION['msg'] = 'Your Employer placement aggreement has been rejected and this placement has been disregarded';

                             return $this->render('emp_placement_aggreement/emp_placement_aggreement.html.twig', 
                             array('form' => $form->createView(), 'empPlacAggForm' => $empPlacAggForm->createView(),
                            'empPlacAgg' => $latestEmpPlacAgg, 'notes' => $note, 'msg' => $_SESSION['msg'], 'username' => $user));   
                        }             

                   
                   }else{
                        // $file stores the uploaded  file   
                        $file = $empPlacAgg1->getLocation();             
                        // Generate a unique name for numerous file uploads before saving it
                        $fileName = $_SESSION['username']. md5(uniqid()) . '.'.$file->guessExtension();
                        // Move the file to the directory where employer placement aggreement Files are stored
                        $file->move(
                            $this->getParameter('emp_plac_aggreements_directory'),
                            $fileName);

                        // Update the 'empPlacAggreementsFile' property to store the PDF/docx file nameinstead of its contents
                        $empPlacAgg1->setLocation($fileName);
                        $empPlacAgg1->setStatus('uploaded');
                        $time = new \DateTime('now'); 
                        $empPlacAgg1->setPlacement($placement);
                        $empPlacAgg1->setDateUploaded($time);
                        $empPlacAgg1->setLastModified($time);
                        // ... persist the students employer placement aggreement before saving
                        $em->persist($empPlacAgg1);
                        $em->flush(); 
                        $latestEmpPlacAgg = $this->getDoctrine()
                              ->getRepository('AppBundle:EmployerPlacementAggreement')
                              ->findOneBy(array('placement' => $placement->getId()));

                        return $this->render('emp_placement_aggreement/emp_placement_aggreement.html.twig', 
                             array('form' => $form->createView(), 'empPlacAggForm' => $empPlacAggForm->createView(),
                            'empPlacAgg' => $latestEmpPlacAgg, 'msg' => $_SESSION['msg'], 'username' => $user));                     

                   }   
                   
     
              }else{
                  
                   $_SESSION['msg'] = 'You cannot upload an Employer placement aggreement document because your job description has been rejected.';
                   return $this->render('emp_placement_aggreement/emp_placement_aggreement.html.twig', 
                             array('form' => $form->createView(), 'empPlacAggForm' => $empPlacAggForm->createView(),
                            'empPlacAgg' => $latestEmpPlacAgg, 'notes' => $note, 'msg' => $_SESSION['msg'], 'username' => $user));                     

              }
         }else{
               $_SESSION['msg'] = 'You need to upload a job description before uploading an Employer placement aggreement document.';               
               return $this->render('emp_placement_aggreement/emp_placement_aggreement.html.twig', 
                             array('form' => $form->createView(), 'empPlacAggForm' => $empPlacAggForm->createView(),
                            'empPlacAgg' => $latestEmpPlacAgg, 'notes' => $note, 'msg' => $_SESSION['msg'], 'username' => $user));   
         }
                               
    }
    
    
    

    
       
    
    //Handle comments on CV
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()){          
           $not = $form['notes']->getData();
           $notes = filter_var($not, FILTER_SANITIZE_STRING);
           $time = date("Y-m-d H:i:s");   
           $name = $student->getFirstname() . ' ' . $student->getLastname();
           $data = array('name' => $name, 'time' => $time, 'notes' => $notes);

           $note[] = $data;
           $comments = serialize($note);           
           if($latestEmpPlacAgg == '' && $note ){               
           
           }else{
               
               $latestEmpPlacAgg->setNotes($comments);
               $em->flush(); 
               
           }
    
    return $this->render('emp_placement_aggreement/emp_placement_aggreement.html.twig', 
                             array('form' => $form->createView(), 'empPlacAggForm' => $empPlacAggForm->createView(),
                            'empPlacAgg' => $latestEmpPlacAgg, 'notes' => $note, 'msg' => $_SESSION['msg'], 'username' => $user));   
    }

    return $this->render('emp_placement_aggreement/emp_placement_aggreement.html.twig', 
                             array('form' => $form->createView(), 'empPlacAggForm' => $empPlacAggForm->createView(),
                            'empPlacAgg' => $latestEmpPlacAgg, 'notes' => $note, 'msg' => $_SESSION['msg'], 'username' => $user));    
    }

    /**
     * @Route("/emp_placement_aggreement/admin", name="admin_emp_placement_aggreement")
     */
    public function empPlacementAggreementAdminAction(Request $request)
    {

    session_start();
     //Upload employer placement aggreement form
     $empPlacAgg1 = new EmployerPlacementAggreement();
     //Handle displaying of eployer placement aggreement data to the user    
     $form = $this->createFormBuilder($empPlacAgg1)
            ->add('status', ChoiceType::class, array('choices'  => array(
            'Uploaded' => 'uploaded',
            'Accepted' => 'accepted',
            'Rejected' => 'rejected')))
            ->add('notes', TextareaType::class, array('attr' => array(
            'class' => 'col-sm-12', 'style' => 'margin-top:30px', 'rows' => 5)))
            ->add('submit', SubmitType::class, array('attr' => array(
            'label' => 'Submit', 'formnovalidate' => 'formnovalidate', 'class' => 'btn btn-primary btn-lg active', 
                 'style' => 'margin-top:10px')))            
            ->getForm();
 
     $studentId = 1434206;
     $note = array();
     $em = $this->getDoctrine()->getManager();  

     
     $student = $this->getDoctrine()
               ->getRepository('AppBundle:Student')
               ->find($studentId );
     $placement = $student->getPlacement();

     $lm_username = $_SESSION['username'];
     $lineManager = $this->getDoctrine()
        ->getRepository('AppBundle:LineManager')
        ->find($lm_username);

     $latestEmpPlacAgg = '';
     $latestTime = '';
     $prevComs = '';

     $latestEmpPlacAgg = $this->getDoctrine()
               ->getRepository('AppBundle:EmployerPlacementAggreement')
               ->findOneBy(array('placement' => $placement->getId()));

     if($latestEmpPlacAgg){
         $prevComs = $latestEmpPlacAgg->getNotes();
         if($prevComs){
              $note = unserialize($prevComs);              
         }
     } 

     //Handle comments on CV
    $form->handleRequest($request);
    if($form->isSubmitted()){          
           $not = $form['notes']->getData();
           $notes = filter_var($not, FILTER_SANITIZE_STRING);
           $time = date("Y-m-d H:i:s");   
           $name = $lineManager->getFirstname() . ' ' . $lineManager->getLastname();
           $data = array('name' => $name, 'time' => $time, 'notes' => $notes);
           $status = $form['status']->getData();
           $note[] = $data;
           $comments = serialize($note);           
           
           $latestEmpPlacAgg->setNotes($comments);
           $latestEmpPlacAgg->setStatus($status); 
           $em->flush(); 
       
           
           return $this->render('emp_placement_aggreement/admin_emp_placement_aggreement.html.twig', 
           array('form' => $form->createView(), 'empPlacAgg' => $latestEmpPlacAgg, 'notes' => $note));          
       } elseif(($form['status']->getData() !== null) && ($form['notes']->getData() == null)){
           $status = $form['status']->getData();
           $latestEmpPlacAgg->setStatus($status); 
           $em->flush();  
       }
   
    return $this->render('emp_placement_aggreement/admin_emp_placement_aggreement.html.twig', 
           array('form' => $form->createView(), 'empPlacAgg' => $latestEmpPlacAgg, 'notes' => $note)); 
    }
    
       
   
    
    
}
