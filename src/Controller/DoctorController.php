<?php

namespace App\Controller;

use App\Entity\Doctor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\DoctorType;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\DoctorRepository;

final class DoctorController extends AbstractController     
{
    #[Route('/doctor', name: 'app_doctor_index')]
    public function index(request $request , DoctorRepository $doctorRepository  ): Response
    {
        $doctors = $doctorRepository->findAll();

        return $this->render('doctor/index.html.twig', [
            'controller_name' => 'DoctorController',
            'doctors' => $doctors,
             
        ]);
    }
    #[Route('/doctor/new', name: 'app_doctor_new')]
    public function new(Request $request , EntityManagerInterface $entityManager ): Response
    {
      $doctor = new Doctor();

      $form = $this->createForm(DoctorType::class, $doctor);

      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
         $doctor->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($doctor);
            $entityManager->flush();
    
            $this->addFlash('success', 'Doctor created successfully!');
    
            return $this->redirectToRoute('app_doctor_index');
      }

      return $this->render('doctor/new.html.twig', [
            'form' => $form,
      ]);
    }
    
}
