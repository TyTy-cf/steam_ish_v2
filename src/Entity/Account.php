<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DrosalysWeb\ObjectExtensions\Slug\Model\SlugInterface;
use DrosalysWeb\ObjectExtensions\Slug\Model\SlugMethodsTrait;
use DrosalysWeb\ObjectExtensions\Timestamp\Model\CreatedTimestampInterface;
use DrosalysWeb\ObjectExtensions\Timestamp\Model\CreatedTimestampMethodsTrait;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: 'email'), UniqueEntity(fields: 'id'), UniqueEntity(fields: 'name')]
#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account implements SlugInterface, CreatedTimestampInterface
{

    use CreatedTimestampMethodsTrait;
    use SlugMethodsTrait;

    #[ORM\Id, ORM\GeneratedValue('AUTO'), ORM\Column(type: 'integer')]
    #[Groups(['AccountList', 'Account'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['AccountList', 'Account'])]
    #[Assert\NotBlank, Assert\Length(max: 180)]
    private string $name;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['AccountList', 'Account'])]
    #[Assert\NotBlank, Assert\Email(message: "account.constraints.email"), Assert\Length(max: 180)]
    private string $email;

    #[ORM\Column(type: 'string', length: 180, nullable: true)]
    #[Groups(['AccountList', 'Account', 'Game'])]
    private ?string $nickname;

    #[ORM\Column(type: 'float')]
    #[Groups(['AccountList', 'Account'])]
    private float $wallet;

    #[ORM\OneToMany(mappedBy: 'account', targetEntity: Library::class)]
    #[Groups(['Account'])]
    private Collection $libraries;

    #[ORM\OneToMany(mappedBy: 'account', targetEntity: Comment::class)]
    #[Groups(['Account'])]
    private Collection $comments;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Groups(['AccountList', 'Account', 'Game'])]
    private string $slug = '';

    #[ORM\Column(type: 'datetime')]
    #[Groups(['AccountList', 'Account'])]
    protected ?DateTime $createdAt;

    #[ORM\ManyToOne(targetEntity: Country::class)]
    #[Groups(['AccountList', 'Account'])]
    private ?Country $country;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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
     * @return Collection
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
     * @return Collection
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

    #[Groups(['AccountList'])]
    public function getLibrariesCount(): int {
        return count($this->libraries);
    }

    public static function getSlugFields(): array
    {
        return [
            'name',
            'id'
        ];
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
}
