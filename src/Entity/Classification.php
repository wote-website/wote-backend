<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * This entity classes the products for 2 goals: 1st is on link with standards in order to propose standard dispatch of product value when product registration. 2nd link with other product to be able to propose alternative products for a scanned product. There is a topic on the detail level of the classification we need. We would need to link child classifications to parent classification (not installed).
 * @author Ronan GLEMAIN <ronan.glemain@gmail.com>
 * 
 * @ORM\Entity(repositoryClass="App\Repository\ClassificationRepository")
 * @ApiResource(
 *     normalizationContext={"groups"={"classification:read"}},
 *     denormalizationContext={"groups"={"classification:write"}},
 *     collectionOperations={
 *         "get"={
 *             "security"="is_granted('ROLE_MEMBER')"
 *         },
 *     },
 *     itemOperations={
 *         "get"={
 *             "security"="is_granted('ROLE_MEMBER')"
 *         },
 *     }
 * )
 */
class Classification
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"classification:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"classification:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"classification:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"classification:read"})
     */
    private $creationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"classification:read"})
     */
    private $modificationDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Standard", mappedBy="classification")
     * @Groups({"classification:read"})
     */
    private $standards;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="classification")
     * @Groups({"classification:read"})
     */
    private $products;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="classifications")
     * @Groups({"classification:read"})
     */
    private $author;

    public function __construct()
    {
        $this->standards = new ArrayCollection();
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

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getModificationDate(): ?\DateTimeInterface
    {
        return $this->modificationDate;
    }

    public function setModificationDate(?\DateTimeInterface $modificationDate): self
    {
        $this->modificationDate = $modificationDate;

        return $this;
    }

    /**
     * @return Collection|Standard[]
     */
    public function getStandards(): Collection
    {
        return $this->standards;
    }

    public function addStandard(Standard $standard): self
    {
        if (!$this->standards->contains($standard)) {
            $this->standards[] = $standard;
            $standard->setClassification($this);
        }

        return $this;
    }

    public function removeStandard(Standard $standard): self
    {
        if ($this->standards->contains($standard)) {
            $this->standards->removeElement($standard);
            // set the owning side to null (unless already changed)
            if ($standard->getClassification() === $this) {
                $standard->setClassification(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setClassification($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getClassification() === $this) {
                $product->setClassification(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
