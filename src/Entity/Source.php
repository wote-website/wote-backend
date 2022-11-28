<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * This contents the description of the source of information for standards dispatching of paroduct value between design and manufacturing.
 * @author Ronan GLEMAIN <ronan.glemain@gmail.com>
 * 
 * @ORM\Entity(repositoryClass="App\Repository\SourceRepository")
 * @ApiResource(
 *     normalizationContext={"groups"={"source:read"}},
 *     denormalizationContext={"groups"={"source:write"}},
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
class Source
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"source:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"source:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"source:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"source:read"})
     */
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="sources")
     * @Groups({"source:read"})
     */
    private $author;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"source:read"})
     */
    private $creationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"source:read"})
     */
    private $modificationDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Standard", mappedBy="source")
     * @Groups({"source:read"})
     */
    private $standards;

    public function __construct()
    {
        $this->standards = new ArrayCollection();
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

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

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
            $standard->setSource($this);
        }

        return $this;
    }

    public function removeStandard(Standard $standard): self
    {
        if ($this->standards->contains($standard)) {
            $this->standards->removeElement($standard);
            // set the owning side to null (unless already changed)
            if ($standard->getSource() === $this) {
                $standard->setSource(null);
            }
        }

        return $this;
    }
}
