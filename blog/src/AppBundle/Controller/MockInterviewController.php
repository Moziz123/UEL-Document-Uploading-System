<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MockInterviewController extends Controller
{
      /**
     * @Route("/mock_interview", name="mock_interview")
     */
    public function mockInterviewAction(Request $request)
    {
        return $this->render('mock_interview/mock_interview.html.twig');
    }

    /**
     * @Route("/mock_interview/admin", name="admin_mock_interview")
     */
    public function mockInterviewAdminAction(Request $request)
    {
        return $this->render('mock_interview/admin_mock_interview.html.twig');
    }
    
    
}
