<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DepartementRepository")
 */
class Departement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $nom;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Responsable", mappedBy="departement", cascade={"persist", "remove"})
     */
    private $responsable;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getResponsable(): ?Responsable
    {
        return $this->responsable;
    }

    public function setResponsable(Responsable $responsable): self
    {
        $this->responsable = $responsable;

        // set the owning side of the relation if necessary
        if ($this !== $responsable->getDepartement()) {
            $responsable->setDepartement($this);
        }

        return $this;
    }
}
