<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * This entity describes the products registered in the database.
 * @author Ronan GLEMAIN <ronan.glemain@gmail.com>
 * 
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ApiResource(
 *     normalizationContext={"groups"={"product:read"}},
 *     denormalizationContext={"groups"={"product:write"}},
 *     collectionOperations={
 *         "get"={
 *             "security"="is_granted('ROLE_MEMBER')"
 *         },
 *         "post"={
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
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"product:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"product:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"product:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"product:read"})
     */
    private $creationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"product:read"})
     */
    private $modificationDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"product:read"})
     */
    private $barcode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"product:read"})
     */
    private $reference;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Classification", inversedBy="products")
     * @Groups({"product:read"})
     */
    private $classification;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"product:read"})
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Production", mappedBy="product")
     * @Groups({"product:read"})
     */
    private $productions;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"product:read"})
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"product:read"})
     */
    private $brand;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductScale", mappedBy="product")
     */
    private $productScales;

    /**
     * @ORM\OneToMany(targetEntity=Score::class, mappedBy="product")
     */
    private $scores;

    public function __construct()
    {
        $this->productions = new ArrayCollection();
        $this->productScales = new ArrayCollection();
        $this->scores = new ArrayCollection();
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

    public function getBarcode(): ?string
    {
        return $this->barcode;
    }

    public function setBarcode(?string $barcode): self
    {
        $this->barcode = $barcode;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getClassification(): ?Classification
    {
        return $this->classification;
    }

    public function setClassification(?Classification $classification): self
    {
        $this->classification = $classification;

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

    /**
     * @return Collection|Production[]
     */
    public function getProductions(): Collection
    {
        return $this->productions;
    }

    public function addProduction(Production $production): self
    {
        if (!$this->productions->contains($production)) {
            $this->productions[] = $production;
            $production->setProduct($this);
        }

        return $this;
    }

    public function removeProduction(Production $production): self
    {
        if ($this->productions->contains($production)) {
            $this->productions->removeElement($production);
            // set the owning side to null (unless already changed)
            if ($production->getProduct() === $this) {
                $production->setProduct(null);
            }
        }

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return Collection|ProductScale[]
     */
    public function getProductScales(): Collection
    {
        return $this->productScales;
    }

    public function addProductScale(ProductScale $productScale): self
    {
        if (!$this->productScales->contains($productScale)) {
            $this->productScales[] = $productScale;
            $productScale->setProduct($this);
        }

        return $this;
    }

    public function removeProductScale(ProductScale $productScale): self
    {
        if ($this->productScales->contains($productScale)) {
            $this->productScales->removeElement($productScale);
            // set the owning side to null (unless already changed)
            if ($productScale->getProduct() === $this) {
                $productScale->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Score[]
     */
    public function getScores(): Collection
    {
        return $this->scores;
    }

    public function addScore(Score $score): self
    {
        if (!$this->scores->contains($score)) {
            $this->scores[] = $score;
            $score->setProduct($this);
        }

        return $this;
    }

    public function removeScore(Score $score): self
    {
        if ($this->scores->contains($score)) {
            $this->scores->removeElement($score);
            // set the owning side to null (unless already changed)
            if ($score->getProduct() === $this) {
                $score->setProduct(null);
            }
        }

        return $this;
    }
}
