<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * Standard User class from Symfony. Addition of special fields for calculation and registration from API.
 * @author Ronan GLEMAIN <ronan.glemain@gmail.com>
 * 
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @ApiResource(
 *     normalizationContext={"groups"={"user:read"}},
 *     denormalizationContext={"groups"={"user:write"}},
 *     collectionOperations={
 *         "post"={
 *             "path"="/register_api",
 *             "controller"=App\Controller\Api\ApiUserController::class
 *         },
 *     },
 *     itemOperations={
 *         "get"={
 *             "security"="is_granted('USER_VIEW', object)"
 *         },
 *         "put"={
 *             "security"="is_granted('ROLE_USER')",
 *             "controller"=App\Controller\Api\ApiPasswordController::class
 *         }
 *     }
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"user:read", "user:write"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"user:read"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user:read", "user:write"})
     */
    private $fullName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"user:read", "user:write"})
     */
    private $phone;

    /**
     * The country for the user is the location of consumption for calculation using GDP.
     * @ORM\ManyToOne(targetEntity="App\Entity\Country")
     * @Groups({"user:read", "user:write"})
     */
    private $country;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Profile", mappedBy="author")
     */
    private $editedProfiles;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Profile", inversedBy="users")
     * @Groups({"user:read", "user:write"})
     */
    private $activeProfile;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="author")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Classification", mappedBy="author")
     */
    private $classifications;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Source", mappedBy="author")
     */
    private $sources;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductScale", mappedBy="user")
     */
    private $productScales;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Brand", mappedBy="author")
     */
    private $brands;

    /**
     * This field is added to hold the plain password from the API before encoding. It is never stored, there is not assiciated field in the database. Is erased when just after the encoding. 
     * @Groups({"user:write"})
     * @SerializedName("password")
     */
    private $plainPassword;

    /**
     * This field is used for password update only. Is erased when just after the encoding.
     * @Groups({"user:write"})
     */
    private $oldPassword;

    public function __construct()
    {
        $this->editedProfiles = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->classifications = new ArrayCollection();
        $this->sources = new ArrayCollection();
        $this->productScales = new ArrayCollection();
        $this->brands = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
        $this->oldPassword = null;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

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
     * @return Collection|Profile[]
     */
    public function getEditedProfiles(): Collection
    {
        return $this->editedProfiles;
    }

    public function addEditedProfile(Profile $editedProfile): self
    {
        if (!$this->editedProfiles->contains($editedProfile)) {
            $this->editedProfiles[] = $editedProfile;
            $editedProfile->setAuthor($this);
        }

        return $this;
    }

    public function removeEditedProfile(Profile $editedProfile): self
    {
        if ($this->editedProfiles->contains($editedProfile)) {
            $this->editedProfiles->removeElement($editedProfile);
            // set the owning side to null (unless already changed)
            if ($editedProfile->getAuthor() === $this) {
                $editedProfile->setAuthor(null);
            }
        }

        return $this;
    }

    public function getActiveProfile(): ?Profile
    {
        return $this->activeProfile;
    }

    public function setActiveProfile(?Profile $activeProfile): self
    {
        $this->activeProfile = $activeProfile;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setAuthor($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getAuthor() === $this) {
                $product->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Classification[]
     */
    public function getClassifications(): Collection
    {
        return $this->classifications;
    }

    public function addClassification(Classification $classification): self
    {
        if (!$this->classifications->contains($classification)) {
            $this->classifications[] = $classification;
            $classification->setAuthor($this);
        }

        return $this;
    }

    public function removeClassification(Classification $classification): self
    {
        if ($this->classifications->contains($classification)) {
            $this->classifications->removeElement($classification);
            // set the owning side to null (unless already changed)
            if ($classification->getAuthor() === $this) {
                $classification->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Source[]
     */
    public function getSources(): Collection
    {
        return $this->sources;
    }

    public function addSource(Source $source): self
    {
        if (!$this->sources->contains($source)) {
            $this->sources[] = $source;
            $source->setAuthor($this);
        }

        return $this;
    }

    public function removeSource(Source $source): self
    {
        if ($this->sources->contains($source)) {
            $this->sources->removeElement($source);
            // set the owning side to null (unless already changed)
            if ($source->getAuthor() === $this) {
                $source->setAuthor(null);
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
            $productScale->setUser($this);
        }

        return $this;
    }

    public function removeProductScale(ProductScale $productScale): self
    {
        if ($this->productScales->contains($productScale)) {
            $this->productScales->removeElement($productScale);
            // set the owning side to null (unless already changed)
            if ($productScale->getUser() === $this) {
                $productScale->setUser(null);
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
            $brand->setAuthor($this);
        }

        return $this;
    }

    public function removeBrand(Brand $brand): self
    {
        if ($this->brands->contains($brand)) {
            $this->brands->removeElement($brand);
            // set the owning side to null (unless already changed)
            if ($brand->getAuthor() === $this) {
                $brand->setAuthor(null);
            }
        }

        return $this;
    }


    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }
    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(?string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }
}
