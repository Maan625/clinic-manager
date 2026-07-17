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
use Doctrine\ORM\query;



final class DoctorController extends AbstractController
{
    #[Route('/doctor', name: 'app_doctor_index')]
    public function index(request $request, DoctorRepository $doctorRepository, PaginatorInterface $paginator): Response
    {
        $search = $request->query->getString('d');
        $page = max(1, $request->query->getInt('page', 1));
        if ($search !== '') {
            $query = $doctorRepository->findBySearch($search);
        } else {
            $query = $doctorRepository->findAllQuery();
        }

        $doctors = $paginator->paginate($query, $page, 10);

        return $this->render('doctor/index.html.twig', [
            'doctors' => $doctors,
            'search' => $search,

        ]);
    }
    #[Route('/doctor/new', name: 'app_doctor_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
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
    #[Route('/doctor/{id}', name: 'app_doctor_show')]
    public function show(Doctor $doctor): Response
    {
        return $this->render('doctor/show.html.twig', [
            'doctor' => $doctor,
        ]);
    }
    #[Route('/doctor/{id}/edit', name: 'app_doctor_edit')]
    public function edit(Request $request, Doctor $doctor, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DoctorType::class, $doctor);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Doctor updated successfully!');
            return $this->redirectToRoute('app_doctor_index');
        }

        return $this->render('doctor/edit.html.twig', [
            'form' => $form,
            'doctor' => $doctor,
        ]);
    }
    #[Route('/doctor/{id}/delete', name: 'app_doctor_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Doctor $doctor,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $doctor->getId(), $request->request->get('_token'))) {
            $entityManager->remove($doctor);
            $entityManager->flush();
            $this->addFlash('success', 'Doctor deleted successfully!');
        }
        return $this->redirectToRoute('app_doctor_index');
    }
}
