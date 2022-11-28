<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * This table is copying a cart of products to make the comparison of products. This is normally the job of a session array. This entity will be repaced by session array plus an entity to store comparisons history.
 * @author Ronan GLEMAIN <ronan.glemain@gmail.com>
 * 
 * @ORM\Entity(repositoryClass="App\Repository\ProductScaleRepository")
 * @ApiResource(
 *     normalizationContext={"groups"={"productScale:read"}},
 *     denormalizationContext={"groups"={"productScale:write"}},
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
 *         "put"={
 *             "security"="is_granted('ROLE_MEMBER')"
 *         },
 *     }
 * )
 */
class ProductScale
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"productScale:read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="productScales")
     * @Groups({"productScale:read"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Profile", inversedBy="productScales")
     * @Groups({"productScale:read"})
     */
    private $profile;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="productScales")
     * @Groups({"productScale:read"})
     */
    private $product;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"productScale:read"})
     */
    private $creationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"productScale:read"})
     */
    private $modificationDate;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"productScale:read"})
     */
    private $score;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"productScale:read"})
     */
    private $transparency;

    /**
     * @ORM\Column(type="json", nullable=true)
     * @Groups({"productScale:read"})
     */
    private $status = [];

    /**
     * @ORM\Column(type="json", nullable=true)
     * @Groups({"productScale:read"})
     */
    private $decision = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): self
    {
        $this->profile = $profile;

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

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(?float $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getTransparency(): ?float
    {
        return $this->transparency;
    }

    public function setTransparency(?float $transparency): self
    {
        $this->transparency = $transparency;

        return $this;
    }

    public function getStatus(): ?array
    {
        return $this->status;
    }

    public function setStatus(?array $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDecision(): ?array
    {
        return $this->decision;
    }

    public function setDecision(?array $decision): self
    {
        $this->decision = $decision;

        return $this;
    }
}
