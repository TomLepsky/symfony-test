<?php

namespace App\Entity;

use App\Repository\ProductTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductTypeRepository::class)]
#[UniqueEntity(fields: 'name')]
class ProductType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['model', 'productType'])]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['productType'])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'productType', targetEntity: Model::class)]
    private Collection $models;

    public function __construct()
    {
        $this->models = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Model>
     */
    public function getModels(): Collection
    {
        return $this->models;
    }

    public function addModel(Model $model): self
    {
        if (!$this->models->contains($model)) {
            $this->models->add($model);
            $model->setProductType($this);
        }

        return $this;
    }

    public function removeModel(Model $model): self
    {
        if ($this->models->removeElement($model)) {
            // set the owning side to null (unless already changed)
            if ($model->getProductType() === $this) {
                $model->setProductType(null);
            }
        }

        return $this;
    }
}
