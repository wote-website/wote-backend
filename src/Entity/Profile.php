<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * The profile contains all Weightins and Priorities for one customization of one customer. It is separated from user to be able to share profiles between users and to define a default profile when there is no user logged.
 * @author Ronan GLEMAIN <ronan.glemain@gmail.com>
 * 
 * @ORM\Entity(repositoryClass="App\Repository\ProfileRepository")
 * @ApiResource(
 *     normalizationContext={"groups"={"profile:read"}},
 *     denormalizationContext={"groups"={"profile:write"}},
 *     collectionOperations={
 *         "post"={
 *             "security"="is_granted('ROLE_USER')",
 *             "controller"=App\Controller\Api\ApiProfilePostController::class
 *         },
 *     },
 *     itemOperations={
 *         "get"={
 *             "security"="is_granted('PROFILE_VIEW', object)"
 *         },
 *         "put"={
 *             "security"="is_granted('PROFILE_EDIT', object)"
 *         },
 *     }
 * )
 */
class Profile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"profile:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"profile:read", "profile:write"})
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"profile:read", "profile:write"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"profile:read"})
     */
    private $creationDate;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"profile:read"})
     */
    private $modificationDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="editedProfiles")
     * @Groups({"profile:read"})
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Weighting", mappedBy="profile", orphanRemoval=true)
     * @Groups({"profile:read", "profile:write"})
     */
    private $weightings;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="activeProfile")
     */
    private $users;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $weightingsSum;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Priority", mappedBy="profile", orphanRemoval=true)
     * @Groups({"profile:read"})
     */
    private $priorities;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductScale", mappedBy="profile")
     */
    private $productScales;

    /**
     * @ORM\OneToMany(targetEntity=Score::class, mappedBy="profile")
     */
    private $scores;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"profile:read", "profile:write"})
     */
    private $isPublic;


    public function __construct()
    {
        $this->weightings = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->priorities = new ArrayCollection();
        $this->productScales = new ArrayCollection();
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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

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
            $weighting->setProfile($this);
        }

        return $this;
    }

    public function removeWeighting(Weighting $weighting): self
    {
        if ($this->weightings->contains($weighting)) {
            $this->weightings->removeElement($weighting);
            // set the owning side to null (unless already changed)
            if ($weighting->getProfile() === $this) {
                $weighting->setProfile(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setActiveProfile($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getActiveProfile() === $this) {
                $user->setActiveProfile(null);
            }
        }

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
            $priority->setProfile($this);
        }

        return $this;
    }

    public function removePriority(Priority $priority): self
    {
        if ($this->priorities->contains($priority)) {
            $this->priorities->removeElement($priority);
            // set the owning side to null (unless already changed)
            if ($priority->getProfile() === $this) {
                $priority->setProfile(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProductScale[]
     */
    public function getProductScales(): Collection
    {
        return $this->productScales;
    }

    public function addProductScale(ProductScale $productScale): self
    {
        if (!$this->productScales->contains($productScale)) {
            $this->productScales[] = $productScale;
            $productScale->setProfile($this);
        }

        return $this;
    }

    public function removeProductScale(ProductScale $productScale): self
    {
        if ($this->productScales->contains($productScale)) {
            $this->productScales->removeElement($productScale);
            // set the owning side to null (unless already changed)
            if ($productScale->getProfile() === $this) {
                $productScale->setProfile(null);
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
            $score->setProfile($this);
        }

        return $this;
    }

    public function removeScore(Score $score): self
    {
        if ($this->scores->contains($score)) {
            $this->scores->removeElement($score);
            // set the owning side to null (unless already changed)
            if ($score->getProfile() === $this) {
                $score->setProfile(null);
            }
        }

        return $this;
    }

    public function getIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(?bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }
}
