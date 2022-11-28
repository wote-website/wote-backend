<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * The rating is the initial scale of one country in one indicator. The ratings used in the calculation of score on products and countries level need to be all between 0 and 100. Thus, all ratings have 2 values: initial rating on indicator index and the converted scale between 0 and 100.
 * @author Ronan GLEMAIN <ronan.glemain@gmail.com>
 * 
 * @ORM\Entity(repositoryClass="App\Repository\RatingRepository")
 * @ApiResource(
 *     normalizationContext={"groups"={"rating:read"}},
 *     denormalizationContext={"groups"={"rating:write"}},
 *     collectionOperations={
 *         "get"={},
 *     },
 *     itemOperations={
 *         "get"={},
 *     }
 * )
 */
class Rating
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"rating:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Groups({"rating:read", "criterion:read"})
     */
    private $ratingValue;

    /**
     * @ORM\Column(type="float")
     * @Groups({"rating:read", "criterion:read"})
     */
    private $sourceIndex;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"rating:read"})
     */
    private $ratingDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="ratings")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"rating:read", "criterion:read"})
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Criterion", inversedBy="ratings")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"rating:read"})
     */
    private $criterion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRatingValue(): ?float
    {
        return $this->ratingValue;
    }

    public function setRatingValue(?float $ratingValue): self
    {
        $this->ratingValue = $ratingValue;

        return $this;
    }

    public function getSourceIndex(): ?float
    {
        return $this->sourceIndex;
    }

    public function setSourceIndex(float $sourceIndex): self
    {
        $this->sourceIndex = $sourceIndex;

        return $this;
    }

    public function getRatingDate(): ?\DateTimeInterface
    {
        return $this->ratingDate;
    }

    public function setRatingDate(?\DateTimeInterface $ratingDate): self
    {
        $this->ratingDate = $ratingDate;

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

    public function getCriterion(): ?Criterion
    {
        return $this->criterion;
    }

    public function setCriterion(?Criterion $criterion): self
    {
        $this->criterion = $criterion;

        return $this;
    }
}
