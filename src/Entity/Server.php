<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ServerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=ServerRepository::class)
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={"get"},
 *     normalizationContext={"groups"={"read"}}
 * )
 */
class Server
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private ?string $rawData;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $processedAt;

    /**
     * @ORM\Column(type="json")
     */
    private $data = [];

    /**
     * @ORM\OneToMany(targetEntity=Website::class, mappedBy="server", orphanRemoval=true)
     */
    private $websites;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $search;

    public function __construct()
    {
        $this->websites = new ArrayCollection();
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

    public function getRawData(): ?string
    {
        return $this->rawData;
    }

    public function setRawData(string $rawData): self
    {
        $this->rawData = $rawData;
        $this->processedAt = null;

        return $this;
    }

    public function getProcessedAt(): ?\DateTimeInterface
    {
        return $this->processedAt;
    }

    public function setProcessedAt(?\DateTimeInterface $processedAt): self
    {
        $this->processedAt = $processedAt;

        return $this;
    }

    /**
     * @return Collection|Website[]
     */
    public function getWebsites(): Collection
    {
        return $this->websites;
    }

    public function addWebsite(Website $website): self
    {
        if (!$this->websites->contains($website)) {
            $this->websites[] = $website;
            $website->setServer($this);
        }

        return $this;
    }

    public function removeWebsite(Website $website): self
    {
        if ($this->websites->removeElement($website)) {
            // set the owning side to null (unless already changed)
            if ($website->getServer() === $this) {
                $website->setServer(null);
            }
        }

        return $this;
    }

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(?string $search): self
    {
        $this->search = $search;

        return $this;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(?array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function __toString()
    {
        return $this->getName() ?? static::class;
    }
}
