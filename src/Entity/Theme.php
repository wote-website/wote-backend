<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * A theme holds a group of indicators (criteria) by nature. There are only 3 themes: Ecology, Fundamental rights and Society choices. They are linked to the Priority weightings of the profile.
 * @author Ronan GLEMAIN <ronan.glemain@gmail.com>
 * 
 * @ORM\Entity(repositoryClass="App\Repository\ThemeRepository")
 * @ApiResource(
 *     normalizationContext={"groups"={"theme:read"}},
 *     denormalizationContext={"groups"={"theme:write"}},
 *     collectionOperations={
 *         "get"={},
 *     },
 *     itemOperations={
 *         "get"={},
 *     }
 * )
 */
class Theme
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"theme:read", "score:read", "profile:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"theme:read", "score:read", "profile:read"})
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"theme:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true, unique=true)
     * @Groups({"theme:read", "profile:read"})
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Criterion", mappedBy="theme")
     * @Groups({"theme:read"})
     */
    private $criteria;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Priority", mappedBy="theme")
     */
    private $priorities;

    /**
     * @ORM\OneToMany(targetEntity=Score::class, mappedBy="theme")
     */
    private $scores;

    public function __construct()
    {
        $this->criteria = new ArrayCollection();
        $this->priorities = new ArrayCollection();
        $this->scores = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(?int $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection|Criterion[]
     */
    public function getCriteria(): Collection
    {
        return $this->criteria;
    }

    public function addCriterion(Criterion $criterion): self
    {
        if (!$this->criteria->contains($criterion)) {
            $this->criteria[] = $criterion;
            $criterion->setTheme($this);
        }

        return $this;
    }

    public function removeCriterion(Criterion $criterion): self
    {
        if ($this->criteria->contains($criterion)) {
            $this->criteria->removeElement($criterion);
            // set the owning side to null (unless already changed)
            if ($criterion->getTheme() === $this) {
                $criterion->setTheme(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Priority[]
     */
    public function getPriorities(): Collection
    {
        return $this->priorities;
    }

    public function addPriority(Priority $priority): self
    {
        if (!$this->priorities->contains($priority)) {
            $this->priorities[] = $priority;
            $priority->setTheme($this);
        }

        return $this;
    }

    public function removePriority(Priority $priority): self
    {
        if ($this->priorities->contains($priority)) {
            $this->priorities->removeElement($priority);
            // set the owning side to null (unless already changed)
            if ($priority->getTheme() === $this) {
                $priority->setTheme(null);
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
            $score->setTheme($this);
        }

        return $this;
    }

    public function removeScore(Score $score): self
    {
        if ($this->scores->contains($score)) {
            $this->scores->removeElement($score);
            // set the owning side to null (unless already changed)
            if ($score->getTheme() === $this) {
                $score->setTheme(null);
            }
        }

        return $this;
    }

}
