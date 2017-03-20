<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Student;
use AppBundle\Entity\Cv;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use AppBundle\Form\CVType;
class CVController extends Controller

{
    
    /**
     * @Route("/cv", name="view_cv")
     */
    public function showCVAction(Request $request)
    {
    session_start();
    $_SESSION['msg'] = '';
    //Upload CV form
    $cv1 = new Cv();
    //Handle displaying of CV data to the user    
    $note = array();

    $em = $this->getDoctrine()->getManager();            
    $cvForm = $this->createForm(CVType::class, $cv1)            
            ->add('submit', SubmitType::class, array('attr' => array(
            'label' => 'Upload', 'class' => 'btn btn-primary btn-lg active', 
             'style' => 'margin-top:10px')));

    $user = $_SESSION['username']; 
    $username = substr($user, 1);    
    $student = $this->getDoctrine()
               ->getRepository('AppBundle:Student')
               ->find($username);
    $cvs = $student->getCvs();
    $latestCV = '';
    $latestTime = '';
    $prevComs = '';                         
    
    if($cvs){
    
        foreach($cvs as $cv){
            
            $dateTime = $cv->getLastModified()->format('Y-m-d H:i:s');
            $timestamp = strtotime($dateTime);
            
            if ($timestamp > $latestTime){
                $latestTime = $timestamp;
                $latestCV = $cv;
                $prevComs = $latestCV->getNotes();
            }                        
        }
        if($prevComs){
              $note = unserialize($prevComs);              
        }
    }  

    //Comments form  
    $cv = new Cv();    
    $form = $this->createFormBuilder($cv)
            ->add('notes', TextareaType::class, array('attr' => array(
            'class' => 'col-sm-12', 'style' => 'margin-top:30px', 'rows' => 5)))
            ->add('submit', SubmitType::class, array('attr' => array(
            'label' => 'Submit', 'class' => 'btn btn-primary btn-lg active', 
             'style' => 'margin-top:10px')))
            ->getForm();  
    

    //Handle uploading of CV
    $cvForm->handleRequest($request);
    if ($cvForm->isSubmitted() && $cvForm->isValid()) {
        
            foreach($cvs as $cv){
    
                if($cv->getStatus() == 'uploaded'){
                   $_SESSION['msg'] = 'There is a CV currently uploaded.  
                   Please wait for a decision before uploading another CV.';

                   return $this->render('cv/student_cv.html.twig', 
                   array('form' => $form->createView(), 'cvForm' => $cvForm->createView(),
                   'cv' => $latestCV, 'notes' => $note, 'msg' => $_SESSION['msg'], 'student' => $student));                        
                }elseif($cv->getStatus() == 'accepted'){
                   $_SESSION['msg'] = 'Your CV has been accepted.  You cannot upload another CV.';

                   return $this->render('cv/student_cv.html.twig', 
                   array('form' => $form->createView(), 'cvForm' => $cvForm->createView(),
                   'cv' => $latestCV, 'notes' => $note, 'msg' => $_SESSION['msg'], 'student' => $student));  
                }
            }


                 // $file stores the uploaded  file   
            $file = $cv1->getLocation();             
            // Generate a unique name for numerous file uploads before saving it
            $fileName = $_SESSION['username']. md5(uniqid()) . '.'.$file->guessExtension();
            // Move the file to the directory where cvFiles are stored
            $file->move(
                $this->getParameter('cvs_directory'),
                $fileName);

            /* Update the 'cvFile' property to store the PDF/docx file name
               instead of its contents*/
            $cv1->setLocation($fileName);
            $cv1->setStatus('uploaded');
            $time = new \DateTime('now'); 
            $cv1->setStudent($student);
            $cv1->setDateUploaded($time);
            $cv1->setLastModified($time);
             // ... persist the students cv before saving
            $em->persist($cv1);
            $savedStudent = $student->addCv($cv1);
            $em->persist($student);
            $em->flush(); 
			
            $newCvs = $savedStudent->getCvs();
            if($newCvs){
                foreach($newCvs as $cv){
                    $dateTime = $cv->getLastModified()->format('Y-m-d H:i:s');
                    $timestamp = strtotime($dateTime);
            
                    if ($timestamp > $latestTime){
                        $latestTime = $timestamp;
                        $latestCV = $cv;
                        $prevComs = $latestCV->getNotes();
                    }                        
                }
                if($prevComs){
                     $note = unserialize($prevComs);              
                }
            }    

            return $this->render('cv/student_cv.html.twig', 
                   array('form' => $form->createView(), 'cvForm' => $cvForm->createView(),
                   'cv' => $latestCV, 'notes' => $note, 'msg' => $_SESSION['msg'], 'student' => $student)); 

        
       
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
           if($latestCV == '' && $note ){               
           
           }else{
               $em = $this->getDoctrine()->getManager();
               $latestCV->setNotes($comments);
               $em->flush(); 
           }         
                  
    }

  return $this->render('cv/student_cv.html.twig', array('form' => $form->createView(), 'cvForm' => $cvForm->createView(),
                        'cv' => $latestCV, 'notes' => $note, 'msg' => $_SESSION['msg'], 'student' => $student));
  }






    
    

    /**
     * @Route("/admin_cv", name="admin_cv")
     */
    public function showAdminCVAction(Request $request)
    {
     session_start();
     $cv = new Cv();
     $form = $this->createFormBuilder($cv)
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
        ->find($studentId);
     $cvs = $student->getCvs();

     $lm_username = $_SESSION['username'];
     $lineManager = $this->getDoctrine()
        ->getRepository('AppBundle:LineManager')
        ->find($lm_username);
     $prevComs = '';
     $latestCV = '';
     $latestTime = ''; 
     if($cvs){
        foreach($cvs as $cv){
            $dateTime = $cv->getLastModified()->format('Y-m-d H:i:s');
            $timestamp = strtotime($dateTime);
            if ($timestamp > $latestTime){
                $latestTime = $timestamp;
                $latestCV = $cv;
                $prevComs = $latestCV->getNotes();
            }                        
        }
        if($prevComs){
              $note = unserialize($prevComs);              
        }
    }      
     

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
           
           
           $latestCV->setNotes($comments);
           $latestCV->setStatus($status); 
           $em->flush(); 
       
           
           return $this->render('cv/admin_cv.html.twig', array('form' => $form->createView(),
                        'cv' => $latestCV, 'notes' => $note));           
       } elseif(($form['status']->getData() !== null) && ($form['notes']->getData() == null)){
           $status = $form['status']->getData();
           $latestCV->setStatus($status); 
           $em->flush();  
       }
    
    return $this->render('cv/admin_cv.html.twig', array('form' => $form->createView(),
                        'cv' => $latestCV, 'notes' => $note));    
    
    }

    
 
        //ini_set('session.save_handler', 'files');
        //ini_set('session.save_path', '/tmp');
        
   
   

    
    
}
