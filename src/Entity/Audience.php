<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AudienceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AudienceRepository::class)
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={"get"},
 *     normalizationContext={"groups"={"read"}}
 * )
 */
class Audience
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     * @Groups({"read"})
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Website::class, mappedBy="audiences")
     */
    private $websites;

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
            $website->addAudience($this);
        }

        return $this;
    }

    public function removeWebsite(Website $website): self
    {
        if ($this->websites->removeElement($website)) {
            $website->removeAudience($this);
        }

        return $this;
    }
}
