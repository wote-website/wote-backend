<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ScoreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * The Score entity is a particular entity as this work is only to hold the result of score calculation as an object. Normally is doens't need to be persisted in database however the exposition through api_platform request to have an id to make the IRI link. So all scores are persisted... One score can be linked to Product or Country and can represent each of 3 levels: observed item, its Themes or Criteria.
 * @author Ronan GLEMAIN <ronan.glemain@gmail.com>
 * 
 * @ApiResource(
 *     normalizationContext={"groups"={"score:read"}},
 *     denormalizationContext={"groups"={"score:write"}},
 *     collectionOperations={
 *         "all_scores_for_contries"={
 *             "controller"=App\Controller\Api\ApiScoreController::class,
 *             "path"="/scores/countries",
 *             "method"="GET"
 *         },
 *     },
 *     itemOperations={
 *         "get"={},
 *     }
 * )
 * @ORM\Entity(repositoryClass=ScoreRepository::class)
 */
class Score
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"score:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"score:read"})
     */
    private $value;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"score:read"})
     */
    private $coverage;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="scores")
     * @Groups({"score:read"})
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=Country::class, inversedBy="scores")
     * @Groups({"score:read"})
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity=Theme::class, inversedBy="scores")
     * @Groups({"score:read"})
     */
    private $theme;

    /**
     * @ORM\ManyToOne(targetEntity=Criterion::class, inversedBy="scores")
     * @Groups({"score:read"})
     */
    private $criterion;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"score:read"})
     */
    private $creationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"score:read"})
     */
    private $modificationDate;

    /**
     * @ORM\ManyToOne(targetEntity=Profile::class, inversedBy="scores")
     * @Groups({"score:read"})
     */
    private $profile;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(?float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getCoverage(): ?float
    {
        return $this->coverage;
    }

    public function setCoverage(?float $coverage): self
    {
        $this->coverage = $coverage;

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

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getTheme(): ?Theme
    {
        return $this->theme;
    }

    public function setTheme(?Theme $theme): self
    {
        $this->theme = $theme;

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

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }
}
