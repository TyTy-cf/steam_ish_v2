<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DrosalysWeb\ObjectExtensions\Slug\Model\SlugInterface;
use DrosalysWeb\ObjectExtensions\Slug\Model\SlugTrait;
use JetBrains\PhpStorm\Pure;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[UniqueEntity(fields: 'name', message: 'account.constraints.unique.name')]
#[ORM\Entity(repositoryClass: GenreRepository::class)]
class Genre implements SlugInterface
{

    use SlugTrait;

    #[ORM\Id, ORM\GeneratedValue('AUTO'), ORM\Column(type: 'integer')]
    #[Groups(['Genre'])]
    private int $id;

    #[ORM\ManyToMany(targetEntity: Game::class, mappedBy: 'genres')]
    #[Groups(['Genre'])]
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
            $game->addGenre($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->removeElement($game)) {
            $game->removeGenre($this);
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
