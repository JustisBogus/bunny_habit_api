<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompletedHabitRepository")
 */
class CompletedHabit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("completedHabit")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("completedHabit")
     */
    private $habit;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("completedHabit")
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("completedHabit")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     * @Groups("completedHabit")
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     * @Groups("completedHabit")
     */
    private $successive;

    /**
     * @Groups("completedHabit")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @Groups("user")
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="completedHabits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHabit(): ?string
    {
        return $this->habit;
    }

    public function setHabit(string $habit): self
    {
        $this->habit = $habit;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSuccessive(): ?int
    {
        return $this->successive;
    }

    public function setSuccessive(int $successive): self
    {
        $this->successive = $successive;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param UserInterface $user
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;

        return $this;
    }
}
