<?php

namespace AppBundle\Controller;
use AppBundle\Entity\JobDescription;
use AppBundle\Entity\Placement;
use Symfony\Component\Validator\Constraints\Length;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use AppBundle\Form\JobDescriptionType;
class JobDescriptionController extends Controller
{

 /**
     * @Route("/jobDesc", name="job_desc")
     */
    public function showJobDescAction(Request $request)
    {
    session_start();
    
    $_SESSION['msg'] = '';
    //Upload Job description form
    $jobDesc1 = new JobDescription();
    //Handle displaying of job description data to the user    
    $note = array();

    $em = $this->getDoctrine()->getManager();            
    $jobDescForm = $this->createForm(JobDescriptionType::class, $jobDesc1)            
            ->add('submit', SubmitType::class, array('attr' => array(
            'label' => 'Upload', 'class' => 'btn btn-primary btn-lg active', 
             'style' => 'margin-top:10px')));

    //Comments form  
    $jobDesc = new JobDescription();    
    $form = $this->createFormBuilder($jobDesc)
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

    $latestJobDesc = '';
    $latestTime = '';
    $prevComs = '';
    if ($placement){
    $latestJobDesc = $this->getDoctrine()
               ->getRepository('AppBundle:JobDescription')
               ->findOneBy(array('placement' => $placement->getId()));
    }
    if($latestJobDesc){
         $prevComs = $latestJobDesc->getNotes();
         if($prevComs){
              $note = unserialize($prevComs);              
         }
    }         
    


    //Handle uploading of job description 
    $jobDescForm->handleRequest($request);
    if ($jobDescForm->isSubmitted() && $jobDescForm->isValid()) {
 
        if($placement){  
                
              if($placement->getStatus() == 'uploaded'){

                   //you need to wait for approval before uploading a job description 
                   $_SESSION['msg'] = 'Your placement needs to be approved before uploading a Job description document.';

                   return $this->render('job_desc/student_job_desc.html.twig', 
                   array('form' => $form->createView(), 'jobDescForm' => $jobDescForm->createView(),
                   'jobDesc' => $latestJobDesc, 'notes' => $note, 'msg' => $_SESSION['msg'], 'username' => $user));  
              }elseif($placement->getStatus() == 'accepted'){
                  
                   if($latestJobDesc){
                         //do some checks on job description
                       
                        if($latestJobDesc->getStatus() == 'uploaded'){
                             
                             //you need to wait for approval before uploading a job description 
                             $_SESSION['msg'] = 'There is a Job description document currently uploaded. 
                             Please wait for a decision before uploading another Job Description document.';

                             return $this->render('job_desc/student_job_desc.html.twig', 
                             array('form' => $form->createView(), 'jobDescForm' => $jobDescForm->createView(),
                             'jobDesc' => $latestJobDesc, 'notes' => $note, 'msg' => $_SESSION['msg'], 'username' => $user));  

                        }elseif($latestJobDesc->getStatus() == 'accepted'){
                             $_SESSION['msg'] = 'Your Job description has been accepted.  You cannot upload another document.';

                             return $this->render('job_desc/student_job_desc.html.twig', 
                             array('form' => $form->createView(), 'jobDescForm' => $jobDescForm->createView(),
                             'jobDesc' => $latestJobDesc, 'notes' => $note, 'msg' => $_SESSION['msg'], 'username' => $user));  
                        }else{
                             $_SESSION['msg'] = 'Your Job description has been rejected and this placement has been disregarded.';

                             return $this->render('job_desc/student_job_desc.html.twig', 
                             array('form' => $form->createView(), 'jobDescForm' => $jobDescForm->createView(),
                             'jobDesc' => $latestJobDesc, 'notes' => $note, 'msg' => $_SESSION['msg'], 'username' => $user));  
                        }             

                   
                   }else{
                        // $file stores the uploaded  file   
                        $file = $jobDesc1->getLocation();             
                        // Generate a unique name for numerous file uploads before saving it
                        $fileName = $_SESSION['username']. md5(uniqid()) . '.'.$file->guessExtension();
                        // Move the file to the directory where cvFiles are stored
                        $file->move(
                            $this->getParameter('job_descriptions_directory'),
                            $fileName);

                        // Update the 'jobDescriptionFile' property to store the PDF/docx file nameinstead of its contents
                        $jobDesc1->setLocation($fileName);
                        $jobDesc1->setStatus('uploaded');
                        $time = new \DateTime('now'); 
                        $jobDesc1->setPlacement($placement);
                        $jobDesc1->setDateUploaded($time);
                        $jobDesc1->setLastModified($time);
                        // ... persist the students cv before saving
                        $em->persist($jobDesc1);
                        $em->flush(); 
                        $latestJobDesc = $this->getDoctrine()
                              ->getRepository('AppBundle:JobDescription')
                              ->findOneBy(array('placement' => $placement->getId()));

                        return $this->render('job_desc/student_job_desc.html.twig', 
                        array('form' => $form->createView(), 'jobDescForm' => $jobDescForm->createView(),
                        'jobDesc' => $latestJobDesc, 'msg' => $_SESSION['msg'], 'username' => $user));                       

                   }   
                   
     
              }else{
                  
                   $_SESSION['msg'] = 'You cannot upload a job description document because your placement has been rejected.';
                   return $this->render('job_desc/student_job_desc.html.twig', 
                   array('form' => $form->createView(), 'jobDescForm' => $jobDescForm->createView(),
                   'jobDesc' => $latestJobDesc, 'notes' => $note, 'msg' => $_SESSION['msg'], 'username' => $user));  
              }
         }else{
               $_SESSION['msg'] = 'You need to confirm a placement before uploading a Job description document.';               
               return $this->render('job_desc/student_job_desc.html.twig', 
               array('form' => $form->createView(), 'jobDescForm' => $jobDescForm->createView(),
               'jobDesc' => $latestJobDesc, 'notes' => $note, 'msg' => $_SESSION['msg'], 'username' => $user)); 

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
           if($latestJobDesc == '' && $note ){               
           
           }else{
               
               $latestJobDesc->setNotes($comments);
               $em->flush(); 
               
           }
    
    return $this->render('job_desc/student_job_desc.html.twig', 
               array('form' => $form->createView(), 'jobDescForm' => $jobDescForm->createView(),
               'jobDesc' => $latestJobDesc, 'notes' => $note, 'msg' => $_SESSION['msg'], 'username' => $user)); 
    }

    return $this->render('job_desc/student_job_desc.html.twig', 
               array('form' => $form->createView(), 'jobDescForm' => $jobDescForm->createView(),
               'jobDesc' => $latestJobDesc, 'notes' => $note, 'msg' => $_SESSION['msg'], 'username' => $user)); 
    }



    /**
     * @Route("/adminJobDesc", name="admin_job_desc")
     */
    public function showAdminJobDescAction(Request $request)
    {
    session_start();
     //Upload Job description form
     $jobDesc1 = new JobDescription();
     //Handle displaying of job description data to the user    
     $form = $this->createFormBuilder($jobDesc1)
            ->add('status', ChoiceType::class, array('choices'  => array(
            'Uploaded' => 'uploaded',
            'Accepted' => 'accepted',
            'Rejected' => 'rejected')))
            ->add('notes', TextareaType::class, array('attr' => array(
            'class' => 'col-sm-12', 'style' => 'margin-top:30px', 'rows' => 5, 'empty_data'  => null, 'required' => false)))
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

     $latestJobDesc = '';
     $latestTime = '';
     $prevComs = '';

     $latestJobDesc = $this->getDoctrine()
               ->getRepository('AppBundle:JobDescription')
               ->findOneBy(array('placement' => $placement->getId()));

     if($latestJobDesc){
         $prevComs = $latestJobDesc->getNotes();
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
           
           $latestJobDesc->setNotes($comments);
           $latestJobDesc->setStatus($status); 
           $em->flush(); 
       
           
           return $this->render('job_desc/admin_job_desc.html.twig', 
               array('form' => $form->createView(), 
               'jobDesc' => $latestJobDesc, 'notes' => $note));          
       } elseif(($form['status']->getData() !== null) && ($form['notes']->getData() == null)){
           $status = $form['status']->getData();
           $latestJobDesc->setStatus($status); 
           $em->flush();  
       }
   
    return $this->render('job_desc/admin_job_desc.html.twig', 
               array('form' => $form->createView(), 
               'jobDesc' => $latestJobDesc, 'notes' => $note)); 
    }
    
}
