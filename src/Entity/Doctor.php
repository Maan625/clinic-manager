<?php

namespace App\Entity;

use App\Repository\DoctorRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: DoctorRepository::class)]
class Doctor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'First name is required.')]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'First name must be at least {{ limit }} characters long.',
        maxMessage: 'First name cannot be longer than {{ limit }} characters.',
    )]
    private ?string $firstName = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Last name is required.')]
    #[Assert\Length(
        min: 2,
        max: 100,
        minMessage: 'Last name must be at least {{ limit }} characters long.',
        maxMessage: 'Last name cannot be longer than {{ limit }} characters.',
    )]
    private ?string $lastName = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank(message: 'Email is required.')]
    #[Assert\Email(message: 'Invalid email format.')]
    private ?string $email = null;

    #[ORM\Column(length: 30, nullable: true)]
    #[Assert\Length(
        max: 30,
        maxMessage: 'Phone number cannot be longer than {{ limit }} characters.',
    )]
    private ?string $phone = null;

    #[ORM\Column(length: 150)]
    #[Assert\NotBlank(message: 'Specialty is required.')]
    #[Assert\Length(
        min: 2,
        max: 150,
        minMessage: 'Specialty must be at least {{ limit }} characters long.',
        maxMessage: 'Specialty cannot be longer than {{ limit }} characters.',
    )]
    private ?string $specialty = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getSpecialty(): ?string
    {
        return $this->specialty;
    }

    public function setSpecialty(string $specialty): static
    {
        $this->specialty = $specialty;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
