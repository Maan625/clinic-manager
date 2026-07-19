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
    public function index(AppointmentRepository $appointmentRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $search = $request->query->getString('a');
        $page = max(1, $request->query->getInt('page', 1));
        if ($search !== '') {
            $query = $appointmentRepository->findBySearch($search);
        } else {
            $query = $appointmentRepository->findAllQuery();
        }

        $appointment = $paginator->paginate($query, $page, 10);
        return $this->render('appointment/index.html.twig', [
            'appointments' => $appointment,
            'search' => $search,
        ]);
    }
    #[Route('/appointment/new', name: 'app_appointment_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, AppointmentRepository $appointmentRepository): Response
    {
        $appointment = new Appointment();
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($appointmentRepository->hasConflict($appointment)) {
                $this->addFlash('danger', 'This doctor already has an appointment at this time.');
                return $this->redirectToRoute('app_appointment_index');
                
            };


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
    #[Route('/appointment/{id}', name: 'app_appointment_show', methods: ['GET'])]
    public function show(Appointment $appointment): Response
    {
        return $this->render('appointment/show.html.twig', [
            'appointment' => $appointment,
        ]);
    }
    #[Route('/appointment/{id}/edit', name: 'app_appointment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Appointment $appointment, EntityManagerInterface $entityManager , AppointmentRepository $appointmentRepository): Response
    {
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($appointmentRepository->hasConflict($appointment)) {
                $this->addFlash('danger', 'This doctor already has an appointment at this time.');
                return $this->redirectToRoute('app_appointment_index');
                
            };
            $entityManager->flush();
            $this->addFlash('success', 'Appointment updated successfully!');
            return $this->redirectToRoute('app_appointment_index');
        }
        return $this->render('appointment/edit.html.twig', [
            'form' => $form,
            'appointment' => $appointment,
        ]);
    }
    #[Route('/appointment/{id}', name: 'app_appointment_delete', methods: ['POST'])]
    public function delete(Request $request, Appointment $appointment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $appointment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($appointment);
            $entityManager->flush();
            $this->addFlash('success', 'Appointment deleted successfully!');
        }
        return $this->redirectToRoute('app_appointment_index');
    }

    
}
