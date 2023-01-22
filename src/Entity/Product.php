<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\UniqueConstraint(name: 'name_model', columns: ['name', 'model_id'])]
#[UniqueEntity(fields: ['name', 'model'])]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['product', 'model', 'cart'])]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[ORM\Column(length: 255)]
    #[Groups(['product', 'model', 'cart'])]
    private ?string $name = null;

    #[Assert\Positive]
    #[ORM\Column]
    #[Groups(['product', 'model', 'cart'])]
    private ?int $price = null;

    #[Assert\NotBlank]
    #[ORM\ManyToOne(inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['cart'])]
    private ?Model $model = null;

    #[Groups(['cart'])]
    private int $count = 1;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }

    public function setModel(?Model $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function incrCount(int $val = 1) : void
    {
        $this->count += $val;
    }

    public function decrCount(int $val = 1) : void
    {
        $this->count -= $val;
    }

    public function getCount() : int
    {
        return $this->count;
    }
}
