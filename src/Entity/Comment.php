<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use DrosalysWeb\ObjectExtensions\Timestamp\Model\CreatedTimestampInterface;
use DrosalysWeb\ObjectExtensions\Timestamp\Model\CreatedTimestampTrait;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment implements CreatedTimestampInterface
{

    use CreatedTimestampTrait;

    #[ORM\Id, ORM\GeneratedValue('AUTO'), ORM\Column(type: 'integer')]
    #[Groups(['Comment'])]
    private int $id;

    #[ORM\Column(type: 'text')]
    #[Groups(['Comment'])]
    private string $content;

    #[ORM\Column(type: 'integer')]
    #[Groups(['Comment'])]
    private int $upVotes;

    #[ORM\Column(type: 'integer')]
    #[Groups(['Comment'])]
    private int $downVotes;

    #[ORM\ManyToOne(targetEntity: Account::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['Comment'])]
    private Account $account;

    #[ORM\ManyToOne(targetEntity: Game::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['Comment'])]
    private Game $game;

    public function __construct()
    {
        $this->downVotes = 0;
        $this->upVotes = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime|null $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
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
