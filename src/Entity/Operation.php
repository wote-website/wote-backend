<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Each Product is splitted in Productions to track the value of the product in all countries involved in the manufacturing. The Operation is the name of a stadard Production part. Mainly ther are only Design (brand origin) and Manufacturing (Made in origin) that are commonly used.
 * @author Ronan GLEMAIN <ronan.glemain@gmail.com>
 * 
 * @ORM\Entity(repositoryClass="App\Repository\OperationRepository")
 * @ApiResource(
 *     normalizationContext={"groups"={"operation:read"}},
 *     denormalizationContext={"groups"={"operation:write"}},
 *     collectionOperations={
 *         "get"={},
 *     },
 *     itemOperations={
 *         "get"={},
 *     }
 * )
 */
class Operation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"operation:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"operation:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"operation:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"operation:read"})
     */
    private $creationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"operation:read"})
     */
    private $modificationDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Standard", mappedBy="operation")
     * @Groups({"operation:read"})
     */
    private $standards;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Production", mappedBy="operation")
     * @Groups({"operation:read"})
     */
    private $productions;

    public function __construct()
    {
        $this->standards = new ArrayCollection();
        $this->productions = new ArrayCollection();
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
            $standard->setOperation($this);
        }

        return $this;
    }

    public function removeStandard(Standard $standard): self
    {
        if ($this->standards->contains($standard)) {
            $this->standards->removeElement($standard);
            // set the owning side to null (unless already changed)
            if ($standard->getOperation() === $this) {
                $standard->setOperation(null);
            }
        }

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
            $production->setOperation($this);
        }

        return $this;
    }

    public function removeProduction(Production $production): self
    {
        if ($this->productions->contains($production)) {
            $this->productions->removeElement($production);
            // set the owning side to null (unless already changed)
            if ($production->getOperation() === $this) {
                $production->setOperation(null);
            }
        }

        return $this;
    }
}
