<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EmployeePlacementAggreementController extends Controller
{
      /**
     * @Route("/emp_placement_aggreement", name="emp_placement_aggreement")
     */
    public function empPlacementAggreementAction(Request $request)
    {
        return $this->render('emp_placement_aggreement/emp_placement_aggreement.html.twig');
    }

    /**
     * @Route("/emp_placement_aggreement/admin", name="admin_emp_placement_aggreement")
     */
    public function empPlacementAggreementAdminAction(Request $request)
    {
        return $this->render('emp_placement_agreement/admin_emp_placement_agreement.html.twig');
    }
    
    
}
