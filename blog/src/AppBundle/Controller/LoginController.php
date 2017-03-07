<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Student;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class LoginController extends Controller
{
    
    /**
     * @Route("/login", name="user_login")
     */
    public function loginAction(Request $request)
    {
        $user = new Student();
        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, array('attr' => array(
            'class' => 'form-control', 'style' => 'margin-botton:15px')))
            ->add('password', PasswordType::class, array('attr' => array(
            'class' => 'form-control', 'style' => 'margin-botton:15px')))
            ->add('login', SubmitType::class, array('attr' => array(
            'label' => 'Login', 'class' => 'btn btn-primary btn-lg active', 
                 'style' => 'margin-top:15px')))
            ->getForm();

        $form->handleRequest($request);       
        
        
        session_start();
        if($form->isSubmitted() && $form->isValid()){
             $username = $form['username']->getData();
             $password = $form['password']->getData();

             $studentUsername = substr($username, 1);

             $studentRepository = $this->getDoctrine()->getRepository('AppBundle:Student');
             $lineManagerRepository = $this->getDoctrine()->getRepository('AppBundle:LineManager');

             $student = $studentRepository->findOneBy( array('username' => 
             $studentUsername, 'password' => $password));

             $lineManager = $lineManagerRepository->findOneBy( array('username' => 
             $username, 'password' => $password));
             
              
             if($student || $lineManager){  
                           
                 
                      if (isset($_SESSION['username'])) {
                            unset($_SESSION['username']);
                      } 
                      $_SESSION['username'] = $username;
                      if($student){
                            
                            return $this->redirectToRoute('job_desc');   
                      }elseif($lineManager){
                            
                            return $this->redirectToRoute('admin_job_desc');
                      }
                
             }
        $_SESSION['messages'] = array('Username and password did not match. Try again.');
        return $this->render('login/login.html.twig', array(
        'form' => $form->createView(), 'messages' => $_SESSION['messages']
        ));      
                     
          
        }

     return $this->render('login/login.html.twig', array(
        'form' => $form->createView()));  

   }
    
}
