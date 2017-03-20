<?php

namespace AppBundle\Controller;

use AppBundle\Entity\EmployerRiskAssessment;
use AppBundle\Entity\Placement;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use AppBundle\Form\EmployerRiskAssessmentType;

class EmployerRiskAssessmentController extends Controller
{
     
     /**
     * @Route("/risk_assessment", name="emp_risk_assessment")
     */
    public function riskAssessmentAction(Request $request)
    {
    session_start();
    
    $_SESSION['msg'] = '';
    //Upload risk form
    $empRiskAss1 = new EmployerRiskAssessment();
    //Handle displaying of employer risk assessment data to the user    
    $note = array();

    $em = $this->getDoctrine()->getManager();            
    $empRiskAssForm = $this->createForm(EmployerRiskAssessmentType::class, $empRiskAss1)            
            ->add('submit', SubmitType::class, array('attr' => array(
            'label' => 'Upload', 'class' => 'btn btn-primary btn-lg active', 
             'style' => 'margin-top:10px')));

    //Comments form  
    $empRiskAss = new EmployerRiskAssessment();    
    $form = $this->createFormBuilder($empRiskAss)
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

    $empPlacementAggreement = '';

    $latestEmpRiskAss = '';
    $latestTime = '';
    $prevComs = '';
    if ($placement){
    $empPlacementAggreement = $this->getDoctrine()
               ->getRepository('AppBundle:EmployerPlacementAggreement')
               ->findOneBy(array('placement' => $placement->getId()));
    
    $latestEmpRiskAss = $this->getDoctrine()
               ->getRepository('AppBundle:EmployerRiskAssessment')
               ->findOneBy(array('placement' => $placement->getId()));
    }
    if($latestEmpRiskAss){
         $prevComs = $latestEmpRiskAss->getNotes();
         if($prevComs){
              $note = unserialize($prevComs);              
         }
    }         
    


    //Handle uploading of employer risk assessment 
    $empRiskAssForm->handleRequest($request);
    if ($empRiskAssForm->isSubmitted() && $empRiskAssForm->isValid()) {
 
        if($empPlacementAggreement){  
                
              if($empPlacementAggreement->getStatus() == 'uploaded'){

                   //you need to wait for approval before uploading a employer risk assessment 
                   $_SESSION['msg'] = 'Your employer placement aggreement needs to be approved before 
                   uploading an employer risk assessment document.';

                   return $this->render('risk_assessment/risk_assessment.html.twig', 
                   array('form' => $form->createView(), 'empRiskAssForm' => $empRiskAssForm->createView(),
                   'empRiskAss' => $latestEmpRiskAss, 'notes' => $note, 'msg' => $_SESSION['msg'], 'username' => $user));  
              }elseif($empPlacementAggreement->getStatus() == 'accepted'){
                  
                   if($latestEmpRiskAss){
                         //do some checks on job description
                       
                        if($latestEmpRiskAss->getStatus() == 'uploaded'){
                             
                             //you need to wait for approval before uploading an employer placement aggreement
                             $_SESSION['msg'] = 'There is an Employer risk assessment document currently uploaded. 
                             Please wait for a decision before uploading another Employer risk assessment document.';

                             return $this->render('risk_assessment/risk_assessment.html.twig', 
                             array('form' => $form->createView(), 'empRiskAssForm' => $empRiskAssForm->createView(),
                             'empRiskAss' => $latestEmpRiskAss, 'notes' => $note, 'msg' => $_SESSION['msg'], 'username' => $user));     

                        }elseif($latestEmpRiskAss->getStatus() == 'accepted'){
                             $_SESSION['msg'] = 'Your Employer risk assessment has been accepted.  You cannot upload another document.';

                             return $this->render('risk_assessment/risk_assessment.html.twig', 
                             array('form' => $form->createView(), 'empRiskAssForm' => $empRiskAssForm->createView(),
                             'empRiskAss' => $latestEmpRiskAss, 'notes' => $note, 'msg' => $_SESSION['msg'], 'username' => $user));   
                        }else{
                             $_SESSION['msg'] = 'Your Employer risk assessment has been rejected and this placement has been disregarded';

                             return $this->render('risk_assessment/risk_assessment.html.twig', 
                             array('form' => $form->createView(), 'empRiskAssForm' => $empRiskAssForm->createView(),
                             'empRiskAss' => $latestEmpRiskAss, 'notes' => $note, 'msg' => $_SESSION['msg'], 'username' => $user));  
                        }             

                   
                   }else{
                        // $file stores the uploaded  file   
                        $file = $empRiskAss1->getLocation();             
                        // Generate a unique name for numerous file uploads before saving it
                        $fileName = $_SESSION['username']. md5(uniqid()) . '.'.$file->guessExtension();
                        // Move the file to the directory where employer placement aggreement Files are stored
                        $file->move(
                            $this->getParameter('emp_risk_assessments_directory'),
                            $fileName);

                        // Update the 'empPlacAggreementsFile' property to store the PDF/docx file nameinstead of its contents
                        $empRiskAss1->setLocation($fileName);
                        $empRiskAss1->setStatus('uploaded');
                        $time = new \DateTime('now'); 
                        $empRiskAss1->setPlacement($placement);
                        $empRiskAss1->setDateUploaded($time);
                        $empRiskAss1->setLastModified($time);
                        // ... persist the students employer placement aggreement before saving
                        $em->persist($empRiskAss1);
                        $em->flush(); 
                        $latestEmpRiskAss = $this->getDoctrine()
                              ->getRepository('AppBundle:EmployerRiskAssessment')
                              ->findOneBy(array('placement' => $placement->getId()));

                        return $this->render('risk_assessment/risk_assessment.html.twig', 
                        array('form' => $form->createView(), 'empRiskAssForm' => $empRiskAssForm->createView(),
                        'empRiskAss' => $latestEmpRiskAss, 'notes' => $note, 'msg' => $_SESSION['msg'], 'username' => $user));                   

                   }   
                   
     
              }else{
                  
                   $_SESSION['msg'] = 'You cannot upload an Employer risk assessment document because your employer placement aggreement has been rejected.';
                   return $this->render('risk_assessment/risk_assessment.html.twig', 
                   array('form' => $form->createView(), 'empRiskAssForm' => $empRiskAssForm->createView(),
                   'empRiskAss' => $latestEmpRiskAss, 'notes' => $note, 'msg' => $_SESSION['msg'], 'username' => $user));

              }
         }else{
               $_SESSION['msg'] = 'You need to upload an Employer placement aggreement before uploading an Employer risk assessment document.';               
               return $this->render('risk_assessment/risk_assessment.html.twig', 
                   array('form' => $form->createView(), 'empRiskAssForm' => $empRiskAssForm->createView(),
                   'empRiskAss' => $latestEmpRiskAss, 'notes' => $note, 'msg' => $_SESSION['msg'], 'username' => $user));   
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
           if($latestEmpRiskAss == '' && $note ){               
           
           }else{
               
               $latestEmpRiskAss->setNotes($comments);
               $em->flush(); 
               
           }
    
    return $this->render('risk_assessment/risk_assessment.html.twig', 
                   array('form' => $form->createView(), 'empRiskAssForm' => $empRiskAssForm->createView(),
                   'empRiskAss' => $latestEmpRiskAss, 'notes' => $note, 'msg' => $_SESSION['msg'], 'username' => $user)); 
    }

    return $this->render('risk_assessment/risk_assessment.html.twig', 
                   array('form' => $form->createView(), 'empRiskAssForm' => $empRiskAssForm->createView(),
                   'empRiskAss' => $latestEmpRiskAss, 'notes' => $note, 'msg' => $_SESSION['msg'], 'username' => $user));     
        
    }

    /**
     * @Route("/risk_assessment/admin", name="admin_emp_risk_assessment")
     */
    public function riskAssessmentAdminAction(Request $request)
    {
       
     session_start();
     //Upload employer risk assessment form
     $empRiskAss1 = new EmployerRiskAssessment();
     //Handle displaying of eployer placement aggreement data to the user    
     $form = $this->createFormBuilder($empRiskAss1)
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

     $latestEmpRiskAss = '';
     $latestTime = '';
     $prevComs = '';

     $latestEmpRiskAss = $this->getDoctrine()
               ->getRepository('AppBundle:EmployerRiskAssessment')
               ->findOneBy(array('placement' => $placement->getId()));

     if($latestEmpRiskAss){
         $prevComs = $latestEmpRiskAss->getNotes();
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
           
           $latestEmpRiskAss->setNotes($comments);
           $latestEmpRiskAss->setStatus($status); 
           $em->flush(); 
       
           
           return $this->render('risk_assessment/admin_risk_assessment.html.twig', 
           array('form' => $form->createView(), 'empRiskAss' => $latestEmpRiskAss, 'notes' => $note));          
       } elseif(($form['status']->getData() !== null) && ($form['notes']->getData() == null)){
           $status = $form['status']->getData();
           $latestEmpRiskAss->setStatus($status); 
           $em->flush();  
       }
   
    return $this->render('risk_assessment/admin_risk_assessment.html.twig', 
           array('form' => $form->createView(), 'empRiskAss' => $latestEmpRiskAss, 'notes' => $note));
    }
    
    
}
