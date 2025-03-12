<?php

namespace App\Entity;

use App\Entity\Traits\Timestamp;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity]
#[Vich\Uploadable]
#[ORM\HasLifecycleCallbacks]
class PageSection
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Page::class, inversedBy: 'sections')]
    private ?Page $page = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private ?string $subtitle = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private ?string $content = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $image = null;

    #[Vich\UploadableField(mapping: 'page_images', fileNameProperty: 'image')]
    private ?File $imageFile = null;

    public function getId(): ?int {
        return $this->id;
    }

    public function getPage(): ?Page {
        return $this->page;
    }

    public function setPage(?Page $page): self {
        $this->page = $page;
        return $this;
    }

    public function getSubtitle(): ?string {
        return $this->subtitle;
    }

    public function setSubtitle(string $subtitle): self {
        $this->subtitle = $subtitle;
        return $this;
    }

    public function getContent(): ?string {
        return $this->content;
    }

    public function setContent(string $content): self {
        $this->content = $content;
        return $this;
    }

    public function setImageFile(?File $file = null): void
    {
        $this->imageFile = $file;

        if ($file) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function __toString(): string
    {
        return $this->getSubtitle() ?: 'Unnamed Section';
    }
}
