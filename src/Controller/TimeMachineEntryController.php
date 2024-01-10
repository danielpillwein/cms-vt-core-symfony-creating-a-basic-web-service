<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class TimeMachineEntryController extends AbstractController
{
    #[Route('/time/machine/entry', name: 'app_time_machine_entry')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TimeMachineEntryController.php',
        ]);
    }
}
