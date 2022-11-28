<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Priority is the profile weighting on theme level. The value can only be positive. Should have been on the same entity as Weightings.
 * @author Ronan GLEMAIN <ronan.glemain@gmail.com>
 * 
 * @ORM\Entity(repositoryClass="App\Repository\PriorityRepository")
 * @ApiResource(
 *     normalizationContext={"groups"={"priority:read"}},
 *     denormalizationContext={"groups"={"priority:write"}},
 *     collectionOperations={},
 *     itemOperations={
 *         "get"={
 *             "security"="is_granted('PRIORITY_VIEW', object)"
 *         },
 *         "put"={
 *             "security"="is_granted('PRIORITY_EDIT', object)"
 *         }
 *     }
 * )
 */
class Priority
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"priority:read", "profile:read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Profile", inversedBy="priorities")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"priority:read"})
     */
    private $profile;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Theme", inversedBy="priorities")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"priority:read", "profile:read"})
     */
    private $theme;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"priority:read", "profile:read", "priority:write"})
     */
    private $value;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $priorizedWeightingsSum;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Weighting", mappedBy="priority")
     */
    private $weightings;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $score;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $transparency;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $weightingsSum;

    public function __construct()
    {
        $this->weightings = new ArrayCollection();
    }

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

    public function getTheme(): ?Theme
    {
        return $this->theme;
    }

    public function setTheme(?Theme $theme): self
    {
        $this->theme = $theme;

        return $this;
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

    public function getPriorizedWeightingsSum(): ?float
    {
        return $this->priorizedWeightingsSum;
    }

    public function setPriorizedWeightingsSum(?float $priorizedWeightingsSum): self
    {
        $this->priorizedWeightingsSum = $priorizedWeightingsSum;

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
            $weighting->setPriority($this);
        }

        return $this;
    }

    public function removeWeighting(Weighting $weighting): self
    {
        if ($this->weightings->contains($weighting)) {
            $this->weightings->removeElement($weighting);
            // set the owning side to null (unless already changed)
            if ($weighting->getPriority() === $this) {
                $weighting->setPriority(null);
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

    public function getWeightingsSum(): ?float
    {
        return $this->weightingsSum;
    }

    public function setWeightingsSum(?float $weightingsSum): self
    {
        $this->weightingsSum = $weightingsSum;

        return $this;
    }
}
