<?php

namespace App\Entity;

use App\Repository\PublisherRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DrosalysWeb\ObjectExtensions\Slug\Model\SlugInterface;
use DrosalysWeb\ObjectExtensions\Slug\Model\SlugTrait;
use DrosalysWeb\ObjectExtensions\Timestamp\Model\CreatedTimestampInterface;
use DrosalysWeb\ObjectExtensions\Timestamp\Model\CreatedTimestampTrait;
use JetBrains\PhpStorm\Pure;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[UniqueEntity(fields: 'name', message: 'account.constraints.unique.name')]
#[ORM\Entity(repositoryClass: PublisherRepository::class)]
class Publisher implements SlugInterface, CreatedTimestampInterface
{

    use SlugTrait;
    use CreatedTimestampTrait;

    #[ORM\Id, ORM\GeneratedValue('AUTO'), ORM\Column(type: 'integer')]
    #[Groups(['Publisher'])]
    private ?int $id;

    #[ORM\Column(type: 'string', length: '180')]
    #[Groups(['Publisher'])]
    private string $name;

    #[ORM\Column(type: 'string', length: '180')]
    #[Groups(['Publisher'])]
    private string $directorName;

    #[ORM\Column(type: 'string', length: '180')]
    #[Groups(['Publisher'])]
    private string $website;

    #[ORM\ManyToOne(targetEntity: Country::class)]
    #[Groups(['Publisher'])]
    private Country $country;

    #[ORM\OneToMany(mappedBy: 'publisher', targetEntity: Game::class)]
    #[Groups(['Publisher'])]
    private Collection $games;

    #[Pure] public function __construct()
    {
        $this->games = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getWebsite(): string
    {
        return $this->website;
    }

    /**
     * @param string $website
     */
    public function setWebsite(string $website): void
    {
        $this->website = $website;
    }

    /**
     * @return string
     */
    public function getDirectorName(): string
    {
        return $this->directorName;
    }

    /**
     * @param string $directorName
     */
    public function setDirectorName(string $directorName): void
    {
        $this->directorName = $directorName;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->setPublisher($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getPublisher() === $this) {
                $game->setPublisher(null);
            }
        }

        return $this;
    }

    public static function getSlugFields(): array
    {
        return [
            'name',
        ];
    }
}
