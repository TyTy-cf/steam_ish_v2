<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DrosalysWeb\ObjectExtensions\Slug\Model\SlugInterface;
use DrosalysWeb\ObjectExtensions\Slug\Model\SlugMethodsTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[UniqueEntity(fields: 'name'), UniqueEntity(fields: 'code')]
#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country implements SlugInterface
{

    use SlugMethodsTrait;

    #[ORM\Id, ORM\GeneratedValue('AUTO'), ORM\Column(type: 'integer')]
    #[Groups(['Country'])]
    private int $id;

    #[ORM\Column(type: 'string', length: '128')]
    #[Groups(['Country'])]
    private string $name;

    #[ORM\Column(type: 'string', length: '128')]
    #[Groups(['Country'])]
    private string $nationality;

    #[ORM\Column(type: 'string', length: '255', nullable: true)]
    #[Groups(['Country'])]
    private ?string $urlFlag;

    #[ORM\Column(type: 'string', length: '2')]
    #[Groups(['Country'])]
    private string $code;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Groups(['Account'])]
    private string $slug = '';

    #[ORM\ManyToMany(targetEntity: Game::class, mappedBy: 'countries')]
    #[Groups(['Comment'])]
    private Collection $games;

    public function __construct()
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Country
     */
    public function setName(string $name): Country
    {
        $this->name = $name;
        return $this;
    }

    public function getUrlFlag(): ?string
    {
        return $this->urlFlag;
    }

    public function setUrlFlag(string $urlFlag): self
    {
        $this->urlFlag = $urlFlag;

        return $this;
    }

    /**
     * @return string
     */
    public function getNationality(): string
    {
        return $this->nationality;
    }

    /**
     * @param string $nationality
     */
    public function setNationality(string $nationality): void
    {
        $this->nationality = $nationality;
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
            $game->addLanguage($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->removeElement($game)) {
            $game->removeLanguage($this);
        }

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public static function getSlugFields(): array
    {
        return [
            'nationality',
        ];
    }
}
