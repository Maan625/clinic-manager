<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Repository\DoctorRepository;
use App\Repository\PatientRepository;
use App\Repository\AppointmentRepository;

#[IsGranted('ROLE_USER')]
final class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard');
        }
        return $this->redirectToRoute('app_login');               
    }
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(DoctorRepository $doctorRepository, PatientRepository $patientRepository, AppointmentRepository $appointmentRepository): Response
    {
        $totalDoctors = $doctorRepository->count([]);
        $totalPatients = $patientRepository->count([]);
        $totalAppointments = $appointmentRepository->count([]);
        $todayAppointments = $appointmentRepository->FindTodyAppointment();
        $statusData = $appointmentRepository->countByStatus();
        $labels = [];
        $data = [];

        foreach ($statusData as $item) {
            $labels[] = $item['status'];
            $data[] = $item['total'];
        }

        return $this->render('dashboard/index.html.twig', [
            'totalDoctors' => $totalDoctors,
            'totalPatients' => $totalPatients,
            'totalAppointments' => $totalAppointments,
            'todayAppointments' => $todayAppointments,
            'labels' => $labels,
            'data' => $data
        ]);
    }
}
