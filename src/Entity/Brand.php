<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * This entity represents the brands of the product the user want to buy. The goal is to link the name of the brand directly to the origin country of the brand. The link should be managed on admin side. It would be used to load the origin country of the brand on new product registration based on the brand name.
 * @author Ronan GLEMAIN <ronan.glemain@gmail.com>
 *  
 * @ORM\Entity(repositoryClass="App\Repository\BrandRepository")
 * @ApiResource(
 *     normalizationContext={"groups"={"brand:read"}},
 *     denormalizationContext={"groups"={"brand:write"}},
 *     collectionOperations={
 *         "get"={
 *             "security"="is_granted('ROLE_MEMBER')"
 *         }
 *     },
 *     itemOperations={
 *         "get"={
 *             "security"="is_granted('ROLE_MEMBER')"
 *         }
 *     }
 * )
 */
class Brand
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"brand:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"brand:read"})
     */
    private $name;

    /**
     * Location of the brand general office
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="brands")
     * @Groups({"brand:read"})
     * 
     */
    private $country;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"brand:read"})
     */
    private $creationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"brand:read"})
     */
    private $modificationDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="brands")
     * @Groups({"brand:read"})
     */
    private $author;

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

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTimeInterface $creationDate): self
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
