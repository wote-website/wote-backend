<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * The weightings store the weight that the user gives on each indicator (criterion) in one profile.
 * @author Ronan GLEMAIN <ronan.glemain@gmail.com>
 * 
 * @ORM\Entity(repositoryClass="App\Repository\WeightingRepository")
 * @ApiResource(
 *     normalizationContext={"groups"={"weighting:read"}},
 *     denormalizationContext={"groups"={"weighting:write"}},
 *     collectionOperations={
 *         "post"={
 *             "security"="is_granted('ROLE_USER')",
 *             "controller"=App\Controller\Api\ApiWeightingPostController::class
 *         },
 *     },
 *     itemOperations={
 *         "get"={
 *             "security"="is_granted('WEIGHTING_VIEW', object)"
 *         },
 *         "put"={
 *             "controller"=App\Controller\Api\ApiWeightingPutController::class,
 *             "security"="is_granted('WEIGHTING_EDIT', object)"
 *         },
 *     }
 * )
 */
class Weighting
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"weighting:read", "profile:read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Profile", inversedBy="weightings")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"weighting:read", "weighting:write"})
     */
    private $profile;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Criterion", inversedBy="weightings")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"weighting:read","profile:read", "weighting:write"})
     */
    private $criterion;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"weighting:read","profile:read"})
     */
    private $creationDate;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"weighting:read","profile:read"})
     */
    private $modificationDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"weighting:read", "weighting:write"})
     */
    private $comment;

    /**
     * @ORM\Column(type="float")
     * @Groups({"weighting:read","profile:read", "weighting:write"})
     */
    private $value;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"weighting:read","profile:read"})
     */
    private $positiveFlag;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"weighting:read","profile:read"})
     */
    private $negativeFlag;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $priorizedValue;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Priority", inversedBy="weightings")
     */
    private $priority;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCriterion(): ?Criterion
    {
        return $this->criterion;
    }

    public function setCriterion(?Criterion $criterion): self
    {
        $this->criterion = $criterion;

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

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
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

    public function getPositiveFlag(): ?bool
    {
        return $this->positiveFlag;
    }

    public function setPositiveFlag(?bool $positiveFlag): self
    {
        $this->positiveFlag = $positiveFlag;

        return $this;
    }

    public function getNegativeFlag(): ?bool
    {
        return $this->negativeFlag;
    }

    public function setNegativeFlag(?bool $negativeFlag): self
    {
        $this->negativeFlag = $negativeFlag;

        return $this;
    }

    public function getPriorizedValue(): ?float
    {
        return $this->priorizedValue;
    }

    public function setPriorizedValue(?float $priorizedValue): self
    {
        $this->priorizedValue = $priorizedValue;

        return $this;
    }

    public function getPriority(): ?Priority
    {
        return $this->priority;
    }

    public function setPriority(?Priority $priority): self
    {
        $this->priority = $priority;

        return $this;
    }
}
