<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PatientRepository;
use App\Entity\Patient;
use App\Form\PatientType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;


final class PatientController extends AbstractController
{
    #[Route('/patients', name: 'app_patient_index')]
    public function index(PatientRepository $repository): Response
    {
        return $this->render('patient/index.html.twig', [
            'patients' => $repository->findAll(),
        ]);
    }

    
    #[Route('/patients/new', name: 'app_patient_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {

        $patient = new Patient();
        $form = $this->createForm(PatientType::class, $patient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $patient->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($patient);
            $entityManager->flush();
            $this->addFlash('success', 'Patient created successfully!');
            return $this->redirectToRoute('app_patient_index');
        }

        return $this->render('patient/new.html.twig', [
            'form' => $form,
        ]);
    }


    #[Route('/patients/{id}', name: 'app_patient_show')]
    public function show(Patient $patient): Response
    {
        return $this->render('patient/show.html.twig', [
            'patient' => $patient,
        ]);
    }
    #[Route('/patients/{id}/edit', name: 'app_patient_edit')]
    public function edit(Request $request, Patient $patient, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PatientType::class, $patient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Patient updated successfully!');
            return $this->redirectToRoute('app_patient_index');
        }

        return $this->render('patient/edit.html.twig', [
            'form' => $form,
            'patient' => $patient,
        ]);
    }
}
