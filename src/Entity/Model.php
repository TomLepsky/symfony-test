<?php

namespace App\Entity;

use App\Repository\ModelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ModelRepository::class)]
#[ORM\UniqueConstraint(name: 'name_producer_productType', columns: ['name', 'producer_id', 'product_type_id'])]
#[UniqueEntity(fields: ['name', 'producer', 'productType'])]
class Model
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['model', 'cart'])]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[ORM\Column(length: 255)]
    #[Groups(['model', 'cart'])]
    private ?string $name = null;

    #[Assert\NotBlank]
    #[ORM\ManyToOne(inversedBy: 'productType')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['model', 'cart'])]
    private ?Producer $producer = null;

    #[Assert\NotBlank]
    #[ORM\ManyToOne(inversedBy: 'models')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['model'])]
    private ?ProductType $productType = null;

    #[ORM\OneToMany(mappedBy: 'model', targetEntity: Product::class)]
    private Collection $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
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

    public function getProducer(): ?Producer
    {
        return $this->producer;
    }

    public function setProducer(?Producer $producer): self
    {
        $this->producer = $producer;

        return $this;
    }

    public function getProductType(): ?ProductType
    {
        return $this->productType;
    }

    public function setProductType(?ProductType $productType): self
    {
        $this->productType = $productType;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setModel($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getModel() === $this) {
                $product->setModel(null);
            }
        }

        return $this;
    }
}
