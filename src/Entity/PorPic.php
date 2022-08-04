<?php

namespace App\Entity;

use App\Repository\PorPicRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PorPicRepository::class)]
class PorPic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $picture;

    #[ORM\Column(type: 'integer')]
    private int $pictureOrder;

    #[ORM\ManyToOne(targetEntity: Portfolio::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $portfolio;

    public function __construct(
        string $picture,
        int $pictureOrder,
        Portfolio $portfolio
    ){
        $this->picture = $picture;
        $this->pictureOrder = $pictureOrder;
        $this->portfolio = $portfolio;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getPictureOrder(): ?int
    {
        return $this->pictureOrder;
    }

    public function setPictureOrder(int $pictureOrder): self
    {
        $this->pictureOrder = $pictureOrder;

        return $this;
    }

    public function getPortfolio(): ?Portfolio
    {
        return $this->portfolio;
    }

    public function setPortfolio(?Portfolio $portfolio): self
    {
        $this->portfolio = $portfolio;

        return $this;
    }
}
