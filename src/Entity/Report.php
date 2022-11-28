<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ReportRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReportRepository::class)
 * @ApiResource(
 *     normalizationContext={"groups"={"report:read"}},
 *     denormalizationContext={"groups"={"report:write"}},
 *     collectionOperations={
 *         "get"={
 *             "controller"=App\Controller\Api\ApiReportController::class
 *         },
 *     },
 *     itemOperations={
 *         "get"={
 *             "controller"=App\Controller\Api\ApiReportController::class
 *         },
 *     }
 * )
 * @Vich\Uploadable
 */
class Report
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"report:read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Criterion::class, inversedBy="reports")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"report:read"})
     */
    private $criterion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"report:read", "criterion:read"})
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"report:read", "criterion:read"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"report:read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"report:read"})
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"report:read", "criterion:read"})
     */
    private $filename;

    /**
     * @Vich\UploadableField(mapping="criterion_report", fileNameProperty="filename", size="size")
     * @var File|null
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"report:read"})
     */
    private $size;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"report:read", "criterion:read"})
     */
    private $link;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"report:read", "criterion:read"})
     */
    private $originalLink;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
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

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Getter on attribut not mapped to ORM, used for Vich File upload
     * @return File Type int required
     */
    public function getFile(): ?File
    {
        return $this->file;
    }
    
    /**
     * @param File|UploadedFile|null $file
     */
    public function setFile(?File $file = null): self
    {
        $this->file = $file;

        if (null !== $file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): self
    {
        $this->size = $size;

        return $this;
    }
    
    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getOriginalLink(): ?string
    {
        return $this->originalLink;
    }

    public function setOriginalLink(?string $originalLink): self
    {
        $this->originalLink = $originalLink;

        return $this;
    }
}
