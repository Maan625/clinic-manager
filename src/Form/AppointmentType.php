<?php

namespace App\Form;

use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Entity\Patient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('appointmentDate', DateTimeType::class, [
                'widget' => 'single_text',
            ])
            ->add('reason')
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Scheduled' => 'scheduled',
                    'Completed' => 'completed',
                    'Cancelled' => 'cancelled',
                ],
            ])
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => 'lastName',
            ])
            ->add('doctor', EntityType::class, [
                'class' => Doctor::class,
                'choice_label' => 'lastName',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
