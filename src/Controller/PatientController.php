<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PatientRepository;

final class PatientController extends AbstractController
{
    #[Route('/patients', name: 'app_patient_index')]
    public function index( PatientRepository $repository ): Response
    {
        return $this->render('patient/index.html.twig', [
            'patients' => $repository->findAll(),
        ]);

    }
}
