<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WebsiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=WebsiteRepository::class)
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={"get"},
 *     normalizationContext={"groups"={"read"}}
 * )
 */
class Website
{
    use TimestampableEntity;

    /**
     * @ORM\ManyToOne(targetEntity=Server::class, inversedBy="websites")
     * @ORM\JoinColumn(referencedColumnName="name", nullable=false)
     */
    private $server;

    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     *
     * @Groups("read")
     */
    private $domain;

    /**
     * @var string
     *
     * @ORM\Column(name="document_root", type="string", length=255, nullable=true)
     *
     * @Groups("read")
     */
    private $documentRoot;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     *
     * @Groups("read")
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="version", type="string", length=255, nullable=true)
     *
     * @Groups("read")
     */
    private $version;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $comments;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $errors;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $updates;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siteRoot;

    /**
     * @ORM\Column(type="json")
     */
    private $data = [];

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $search;

    /**
     * @ORM\ManyToMany(targetEntity=Audience::class, inversedBy="websites")
     * @ORM\JoinTable(
     *     joinColumns={
     *         @ORM\JoinColumn(referencedColumnName="domain", nullable=false)
     *     },
     *     inverseJoinColumns={
     *         @ORM\JoinColumn(referencedColumnName="name", nullable=false)
     *     }
     * )
     */
    private $audiences;

    public function __construct()
    {
        $this->audiences = new ArrayCollection();
    }

    public function getServer(): ?Server
    {
        return $this->server;
    }

    public function setServer(?Server $server): self
    {
        $this->server = $server;

        return $this;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function setDomain(string $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

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

    /**
     * @return Collection|Audience[]
     */
    public function getAudiences(): Collection
    {
        return $this->audiences;
    }

    public function addAudience(Audience $audience): self
    {
        if (!$this->audiences->contains($audience)) {
            $this->audiences[] = $audience;
        }

        return $this;
    }

    public function removeAudience(Audience $audience): self
    {
        $this->audiences->removeElement($audience);

        return $this;
    }

    public function getDocumentRoot(): string
    {
        return $this->documentRoot;
    }

    public function setDocumentRoot(string $documentRoot): Website
    {
        $this->documentRoot = $documentRoot;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): Website
    {
        $this->type = $type;

        return $this;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): Website
    {
        $this->version = $version;

        return $this;
    }

    public function getComments(): string
    {
        return $this->comments;
    }

    public function setComments(string $comments): Website
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param mixed $errors
     *
     * @return Website
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdates()
    {
        return $this->updates;
    }

    /**
     * @param mixed $updates
     *
     * @return Website
     */
    public function setUpdates($updates)
    {
        $this->updates = $updates;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSiteRoot()
    {
        return $this->siteRoot;
    }

    /**
     * @param mixed $siteRoot
     *
     * @return Website
     */
    public function setSiteRoot($siteRoot)
    {
        $this->siteRoot = $siteRoot;

        return $this;
    }
}
