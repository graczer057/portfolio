<?php

namespace App\Entity;

use App\Repository\PortfolioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PortfolioRepository::class)]
class Portfolio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $mainTitle;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $worldLink;

    #[ORM\Column(type: 'string', length: 255)]
    private $githubLink;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    #[ORM\Column(type: 'string', length: 255)]
    private $stack;

    public function __construct(
        string $mainTitle,
        ?string $worldLink,
        string $githubLink,
        string $description,
        string $stack
    ){
        $this->mainTitle = $mainTitle;
        $this->worldLink = $worldLink;
        $this->githubLink = $githubLink;
        $this->description = $description;
        $this->stack = $stack;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMainTitle(): ?string
    {
        return $this->mainTitle;
    }

    public function setMainTitle(string $mainTitle): self
    {
        $this->mainTitle = $mainTitle;

        return $this;
    }

    public function getWorldLink(): ?string
    {
        return $this->worldLink;
    }

    public function setWorldLink(string $worldLink): self
    {
        $this->worldLink = $worldLink;

        return $this;
    }

    public function getGithubLink(): ?string
    {
        return $this->githubLink;
    }

    public function setGithubLink(string $githubLink): self
    {
        $this->githubLink = $githubLink;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStack()
    {
        return $this->stack;
    }

    public function setStack(string $stack): self
    {
        $this->stack = $stack;

        return $this;
    }

}
