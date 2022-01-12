<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AccountRepository::class)
 * @UniqueEntity(fields={"name"}, message="account.constraints.unique.name")
 * @UniqueEntity(fields={"email"}, message="account.constraints.unique.email")
 * @ApiResource()
 */
class Account
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    use TraitSlug;

    /**
     * @ORM\Column(type="string", length=255)
     * @ApiFilter(OrderFilter::class)
     * @Assert\Email(message="account.constraints.email")
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $nickname;

    /**
     * @ORM\Column(type="float")
     */
    private float $wallet;

    /**
     * @ORM\OneToMany(targetEntity=Library::class, mappedBy="account")
     */
    private Collection $libraries;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="account")
     */
    private Collection $comments;

    public function __construct()
    {
        $this->libraries = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->wallet = 0.0;
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

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getWallet(): ?float
    {
        return $this->wallet;
    }

    public function setWallet(float $wallet): self
    {
        $this->wallet = $wallet;

        return $this;
    }

    /**
     * @return Collection|Library[]
     */
    public function getLibraries(): Collection
    {
        return $this->libraries;
    }

    public function addLibrary(Library $library): self
    {
        if (!$this->libraries->contains($library)) {
            $this->libraries[] = $library;
        }

        return $this;
    }

    public function removeLibrary(Library $library): self
    {
        $this->libraries->removeElement($library);

        return $this;
    }
    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        $this->comments->removeElement($comment);

        return $this;
    }
}
