<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'categories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Board::class)]
    private Collection $boards;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    public function __construct()
    {
        $this->boards = new ArrayCollection();
    }

    // id
    public function getId(): ?int
    {
        return $this->id;
    }

    // name
    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    // user
    public function getUser(): ?User
    {
        return $this->user;
    }
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    // board
    /**
     * @return Collection<int, Board>
     */
    public function getBoards(): Collection
    {
        return $this->boards;
    }
    public function addBoard(Board $board): self
    {
        if (!$this->boards->contains($board)) {
            $this->boards->add($board);
            $board->setCategory($this);
        }

        return $this;
    }
    public function removeBoard(Board $board): self
    {
        if ($this->boards->removeElement($board)) {
            // set the owning side to null (unless already changed)
            if ($board->getCategory() === $this) {
                $board->setCategory(null);
            }
        }

        return $this;
    }

    // created_at
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }
    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
