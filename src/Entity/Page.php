<?php

namespace App\Entity;

use App\Entity\Traits\Deletable;
use App\Entity\Traits\Timestamp;
use App\Repository\PageRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: PageRepository::class)]
class Page
{
    use Timestamp;
    use Deletable;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(type: 'text')]
    private string $content;

    #[ORM\OneToMany(targetEntity: PageSection::class, mappedBy: 'page', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $sections;

    public function __construct() {
        $this->sections = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getSections(): Collection {
        return $this->sections;
    }

    public function addSection(PageSection $section): self {
        if (!$this->sections->contains($section)) {
            $this->sections[] = $section;
            $section->setPage($this);
        }
        return $this;
    }
    public function removeSection(PageSection $section): self {
        if ($this->sections->removeElement($section)) {
            if ($section->getPage() === $this) {
                $section->setPage(null);
            }
        }
        return $this;
    }
}