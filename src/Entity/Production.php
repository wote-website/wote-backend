<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Each Product is splitted in Productions to track the value of the product in all countries involved in the manufacturing.
 * @author Ronan GLEMAIN <ronan.glemain@gmail.com>
 * 
 * @ORM\Entity(repositoryClass="App\Repository\ProductionRepository")
 * @ApiResource(
 *     normalizationContext={"groups"={"production:read"}},
 *     denormalizationContext={"groups"={"production:write"}},
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
class Production
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"production:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Groups({"production:read"})
     */
    private $value;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"production:read"})
     */
    private $comment;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"production:read"})
     */
    private $creationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"production:read"})
     */
    private $modificationDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="productions")
     * @Groups({"production:read"})
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Operation", inversedBy="productions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"production:read"})
     */
    private $operation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="productions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"production:read"})
     */
    private $product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

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

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getOperation(): ?Operation
    {
        return $this->operation;
    }

    public function setOperation(?Operation $operation): self
    {
        $this->operation = $operation;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
