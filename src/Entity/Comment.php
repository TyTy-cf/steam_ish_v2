<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use DrosalysWeb\ObjectExtensions\Timestamp\Model\CreatedTimestampInterface;
use DrosalysWeb\ObjectExtensions\Timestamp\Model\CreatedTimestampMethodsTrait;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment implements CreatedTimestampInterface
{

    #[ORM\Id, ORM\GeneratedValue('AUTO'), ORM\Column(type: 'integer')]
    #[Groups(['Comment', 'Account'])]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    #[Groups(['Comment', 'Account'])]
    private string $content;

    #[ORM\Column(type: 'integer')]
    #[Groups(['Comment', 'Account'])]
    private int $upVotes;

    #[ORM\Column(type: 'integer')]
    #[Groups(['Comment', 'Account'])]
    private int $downVotes;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['Account', 'Account'])]
    protected ?DateTime $createdAt;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['Comment'])]
    private Account $account;

    #[ORM\ManyToOne(targetEntity: Game::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['Comment', 'Account'])]
    private Game $game;

    use CreatedTimestampMethodsTrait;

    public function __construct()
    {
        $this->downVotes = 0;
        $this->upVotes = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUpVotes(): int
    {
        return $this->upVotes;
    }

    public function setUpVotes(int $upVotes): self
    {
        $this->upVotes = $upVotes;

        return $this;
    }

    public function getDownVotes(): int
    {
        return $this->downVotes;
    }

    public function setDownVotes(int $downVotes): self
    {
        $this->downVotes = $downVotes;

        return $this;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    public function getGame(): Game
    {
        return $this->game;
    }

    public function setGame(Game $game): self
    {
        $this->game = $game;

        return $this;
    }
}
