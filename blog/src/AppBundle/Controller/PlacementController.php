<?php

namespace AppBundle\Controller;

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
        return $this->render('placement/placement_confirmation.html.twig');
    }

    /**
     * @Route("/placement_confirmation/admin", name="admin_placement_confirmation")
     */
    public function placementConfirmationAdminAction(Request $request)
    {
        return $this->render('placement/admin_placement_confirmation.html.twig');
    }
    
    
}
