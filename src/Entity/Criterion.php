<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * One Criterion represents one indicator type. This contains all description information about the indicators. The ratings of each country in this indicator are stored in the Rating entity.
 * @author Ronan GLEMAIN <ronan.glemain@gmail.com>
 * 
 * @ORM\Entity(repositoryClass="App\Repository\CriterionRepository")
 * @ApiResource(
 *     normalizationContext={"groups"={"criterion:read"}},
 *     denormalizationContext={"groups"={"criterion:write"}},
 *     collectionOperations={
 *         "get"={},
 *     },
 *     itemOperations={
 *         "get"={},
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"designation": "partial"})
 * 
 * @Vich\Uploadable
 */
class Criterion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"criterion:read", "score:read", "profile:read"})
     */
    private $id;

    /**
     * Depreciated field, replaced by title.
     * @ORM\Column(type="string", length=255)
     * @Groups({"criterion:read"})
     */
    private $designation;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"criterion:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=4, nullable=true, unique=true)
     * @Groups({"criterion:read", "score:read"})
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"criterion:read"})
     */
    private $unit;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"criterion:read"})
     */
    private $formula;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"criterion:read"})
     */
    private $source;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"criterion:read"})
     */
    private $sourceLink;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"criterion:read"})
     */
    private $creationDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"criterion:read"})
     */
    private $modificationDate;

    /**
     * @ORM\Column(type="json", nullable=true)
     * @Groups({"criterion:read"})
     */
    private $status = [];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Theme", inversedBy="criteria")
     * @Groups({"criterion:read", "profile:read"})
     */
    private $theme;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country")
     * @Groups({"criterion:read"})
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rating", mappedBy="criterion", orphanRemoval=true)
     * @Groups({"criterion:read"})
     */
    private $ratings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Weighting", mappedBy="criterion")
     */
    private $weightings;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"criterion:read", "profile:read"})
     */
    private $proposal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"criterion:read", "score:read", "profile:read"})
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity=Score::class, mappedBy="criterion")
     */
    private $scores;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"criterion:read"})
     */
    private $logoFilename;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"criterion:read"})
     */
    private $logoOriginalLink;

    /**
     * @Vich\UploadableField(mapping="criterion_logo", fileNameProperty="logoFilename", size="logoSize")
     * @var File|null
     * @Assert\Image()
     */
    private $logoFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"criterion:read"})
     */
    private $logoSize;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"criterion:read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"criterion:read"})
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"criterion:read"})
     */
    private $logoLink;

    /**
     * @ORM\OneToMany(targetEntity=Report::class, mappedBy="criterion", orphanRemoval=true)
     * @Groups({"criterion:read"})
     */
    private $reports;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"criterion:read"})
     */
    private $sourceDescription;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"criterion:read"})
     */
    private $reportOriginalLink;

    public function __construct()
    {
        $this->ratings = new ArrayCollection();
        $this->weightings = new ArrayCollection();
        $this->scores = new ArrayCollection();
        $this->reports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getFormula(): ?string
    {
        return $this->formula;
    }

    public function setFormula(?string $formula): self
    {
        $this->formula = $formula;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getSourceLink(): ?string
    {
        return $this->sourceLink;
    }

    public function setSourceLink(?string $sourceLink): self
    {
        $this->sourceLink = $sourceLink;

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

    public function getStatus(): ?array
    {
        return $this->status;
    }

    public function setStatus(?array $status): self
    {
        $this->status = $status;

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

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

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
            $rating->setCriterion($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->contains($rating)) {
            $this->ratings->removeElement($rating);
            // set the owning side to null (unless already changed)
            if ($rating->getCriterion() === $this) {
                $rating->setCriterion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Weighting[]
     */
    public function getWeightings(): Collection
    {
        return $this->weightings;
    }

    public function addWeighting(Weighting $weighting): self
    {
        if (!$this->weightings->contains($weighting)) {
            $this->weightings[] = $weighting;
            $weighting->setCriterion($this);
        }

        return $this;
    }

    public function removeWeighting(Weighting $weighting): self
    {
        if ($this->weightings->contains($weighting)) {
            $this->weightings->removeElement($weighting);
            // set the owning side to null (unless already changed)
            if ($weighting->getCriterion() === $this) {
                $weighting->setCriterion(null);
            }
        }

        return $this;
    }

    public function getProposal(): ?string
    {
        return $this->proposal;
    }

    public function setProposal(?string $proposal): self
    {
        $this->proposal = $proposal;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

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
            $score->setCriterion($this);
        }

        return $this;
    }

    public function removeScore(Score $score): self
    {
        if ($this->scores->contains($score)) {
            $this->scores->removeElement($score);
            // set the owning side to null (unless already changed)
            if ($score->getCriterion() === $this) {
                $score->setCriterion(null);
            }
        }

        return $this;
    }

    public function getLogoFilename(): ?string
    {
        return $this->logoFilename;
    }

    public function setLogoFilename(?string $logoFilename): self
    {
        $this->logoFilename = $logoFilename;

        return $this;
    }

    public function getLogoOriginalLink(): ?string
    {
        return $this->logoOriginalLink;
    }

    public function setLogoOriginalLink(?string $logoOriginalLink): self
    {
        $this->logoOriginalLink = $logoOriginalLink;

        return $this;
    }

    /**
     * Getter on attribut not mapped to ORM, used for Vich File upload
     * @return File Type int required
     */
    public function getLogoFile(): ?File
    {
        return $this->logoFile;
    }
    
    /**
     * @param File|UploadedFile|null $logoFile
     */
    public function setLogoFile(?File $logoFile = null): self
    {
        $this->logoFile = $logoFile;

        if (null !== $logoFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    public function getLogoSize(): ?string
    {
        return $this->logoSize;
    }

    public function setLogoSize(?string $logoSize): self
    {
        $this->logoSize = $logoSize;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getLogoLink(): ?string
    {
        return $this->logoLink;
    }

    public function setLogoLink(?string $logoLink): self
    {
        $this->logoLink = $logoLink;

        return $this;
    }

    /**
     * @return Collection|Report[]
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): self
    {
        if (!$this->reports->contains($report)) {
            $this->reports[] = $report;
            $report->setCriterion($this);
        }

        return $this;
    }

    public function removeReport(Report $report): self
    {
        if ($this->reports->contains($report)) {
            $this->reports->removeElement($report);
            // set the owning side to null (unless already changed)
            if ($report->getCriterion() === $this) {
                $report->setCriterion(null);
            }
        }

        return $this;
    }

    public function getSourceDescription(): ?string
    {
        return $this->sourceDescription;
    }

    public function setSourceDescription(?string $sourceDescription): self
    {
        $this->sourceDescription = $sourceDescription;

        return $this;
    }

    public function getReportOriginalLink(): ?string
    {
        return $this->reportOriginalLink;
    }

    public function setReportOriginalLink(?string $reportOriginalLink): self
    {
        $this->reportOriginalLink = $reportOriginalLink;

        return $this;
    }
}
