<?php

namespace App\Entity;

use App\Repository\OrganizacaoMilitarRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrganizacaoMilitarRepository::class)]
class OrganizacaoMilitar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nome = null;

    #[ORM\Column(length: 50)]
    private ?string $sigla = null;

    #[ORM\Column(length: 50)]
    private ?string $indicativo = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): static
    {
        $this->nome = $nome;

        return $this;
    }

    public function getSigla(): ?string
    {
        return $this->sigla;
    }

    public function setSigla(string $sigla): static
    {
        $this->sigla = $sigla;

        return $this;
    }

    public function getIndicativo(): ?string
    {
        return $this->indicativo;
    }

    public function setIndicativo(string $indicativo): static
    {
        $this->indicativo = $indicativo;

        return $this;
    }
}
