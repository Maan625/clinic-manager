<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AppointmentRepository;
use App\Entity\Appointment;
use App\Form\AppointmentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;


 
final class AppointmentController extends AbstractController
{
    #[Route('/appointment', name: 'app_appointment_index')]
    public function index(AppointmentRepository $appointmentRepository): Response
    {


        return $this->render('appointment/index.html.twig', [
            'appointments' => $appointmentRepository->findAll(),
        ]);
    }
    #[Route('/appointment/new', name: 'app_appointment_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $appointment = new Appointment();
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $appointment->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($appointment);
            $entityManager->flush();
            $this->addFlash('success', 'Appointment created successfully!');
            return $this->redirectToRoute('app_appointment_index');
        }
        return $this->render('appointment/new.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/appointment/{id}', name: 'app_appointment_show' , methods: ['GET'])]
    public function show(Appointment $appointment): Response
    {
        return $this->render('appointment/show.html.twig', [
            'appointment' => $appointment,
        ]);
    }
}
