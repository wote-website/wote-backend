<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * This entity holding the definition of countries contains economic data to make score calculation.
 * @author Ronan GLEMAIN <ronan.glemain@gmail.com>
 * 
 * @ORM\Entity(repositoryClass="App\Repository\CountryRepository")
 * @ApiResource(
 *     attributes={
 *         "order"={"name":"ASC"}    
 *     },
 *     normalizationContext={"groups"={"country:read"}},
 *     denormalizationContext={"groups"={"country:write"}},
 *     collectionOperations={
 *         "get"={"controller"=App\Controller\Api\ScoredCountryController::class},
 *     },
 *     itemOperations={
 *         "get"={},
 *     }
 * )
 * 
 * Warning 03/06/2020: the filter doesn't work while it is working on Criterion.
 * @ApiFilter(SearchFilter::class, properties={"frName":"partial"})
 */
class Country
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"country:read"})
     */
    private $id;

    /**
     * Main name in english
     * @ORM\Column(type="string", length=255)
     * @Groups({"country:read", "score:read"})
     */
    private $name;

    /**
     * Used as reference for update content update with symfony custom command. Based on standarized 3 letters countries definition.
     * @ORM\Column(type="string", length=3, unique=true)
     * @Groups({"country:read"})
     */
    private $alpha3;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"country:read"})
     */
    private $creationDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"country:read"})
     */
    private $modificationDate;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"country:read"})
     */
    private $gdpPpp;

    /**
     * Value used for the calculation of scores combining many countries in order to reduce the consequence of working cost differences.
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"country:read"})
     */
    private $gdpPppPerCapita;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rating", mappedBy="country", orphanRemoval=true)
     */
    private $ratings;

    /**
     * Field to delete following the creation of Score Entity
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"country:read"})
     */
    private $score;

    /**
     * Field to delete following the creation of Score Entity and renamed as Coverage
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"country:read"})
     */
    private $transparency;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Production", mappedBy="country")
     */
    private $productions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Brand", mappedBy="country")
     */
    private $brands;

    /**
     * @ORM\OneToMany(targetEntity=Score::class, mappedBy="country")
     */
    private $scores;

    /**
     * This translation field is present to give 2 languages to the user in the MVP: EN + FR to be able to make country search while the name can be often found in FR or EN at the market places.
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"country:read", "criterion:read", "score:read"})
     */
    private $frName;

    /**
     * Added to link with language which is often given with 2 letters.
     * @ORM\Column(type="string", length=2, nullable=true)
     * @Groups({"country:read"})
     */
    private $alpha2;

    public function __construct()
    {
        $this->ratings = new ArrayCollection();
        $this->productions = new ArrayCollection();
        $this->brands = new ArrayCollection();
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

    public function getAlpha3(): ?string
    {
        return $this->alpha3;
    }

    public function setAlpha3(string $alpha3): self
    {
        $this->alpha3 = $alpha3;

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

    public function getGdpPpp(): ?float
    {
        return $this->gdpPpp;
    }

    public function setGdpPpp(?float $gdpPpp): self
    {
        $this->gdpPpp = $gdpPpp;

        return $this;
    }

    public function getGdpPppPerCapita(): ?float
    {
        return $this->gdpPppPerCapita;
    }

    public function setGdpPppPerCapita(?float $gdpPppPerCapita): self
    {
        $this->gdpPppPerCapita = $gdpPppPerCapita;

        return $this;
    }

    /**
     * @return Collection|Rating[]
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings[] = $rating;
            $rating->setCountry($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->contains($rating)) {
            $this->ratings->removeElement($rating);
            // set the owning side to null (unless already changed)
            if ($rating->getCountry() === $this) {
                $rating->setCountry(null);
            }
        }

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
            $production->setCountry($this);
        }

        return $this;
    }

    public function removeProduction(Production $production): self
    {
        if ($this->productions->contains($production)) {
            $this->productions->removeElement($production);
            // set the owning side to null (unless already changed)
            if ($production->getCountry() === $this) {
                $production->setCountry(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Brand[]
     */
    public function getBrands(): Collection
    {
        return $this->brands;
    }

    public function addBrand(Brand $brand): self
    {
        if (!$this->brands->contains($brand)) {
            $this->brands[] = $brand;
            $brand->setCountry($this);
        }

        return $this;
    }

    public function removeBrand(Brand $brand): self
    {
        if ($this->brands->contains($brand)) {
            $this->brands->removeElement($brand);
            // set the owning side to null (unless already changed)
            if ($brand->getCountry() === $this) {
                $brand->setCountry(null);
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
            $score->setCountry($this);
        }

        return $this;
    }

    public function removeScore(Score $score): self
    {
        if ($this->scores->contains($score)) {
            $this->scores->removeElement($score);
            // set the owning side to null (unless already changed)
            if ($score->getCountry() === $this) {
                $score->setCountry(null);
            }
        }

        return $this;
    }

    public function getFrName(): ?string
    {
        return $this->frName;
    }

    public function setFrName(?string $frName): self
    {
        $this->frName = $frName;

        return $this;
    }

    public function getAlpha2(): ?string
    {
        return $this->alpha2;
    }

    public function setAlpha2(?string $alpha2): self
    {
        $this->alpha2 = $alpha2;

        return $this;
    }
}
