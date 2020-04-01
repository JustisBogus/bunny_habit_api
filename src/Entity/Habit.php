<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HabitRepository")
 */
class Habit
{
    /**
     * @Groups("habit")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups("habit")
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max=25)
     */
    private $habit;

    /**
     * @Groups("habit")
     * @ORM\Column(type="datetime")
     */
    private $created_date;

    /**
     * @Groups("habit")
     * @ORM\Column(type="datetime")
     */
    private $modified_date;

    /**
     * @Groups("habit")
     * @ORM\Column(type="integer", length=5)
     */
    private $dayly;

    /**
     * @Groups("habit")
     * @ORM\Column(type="integer", length=5)
     */
    private $type;

    /**
     * @Groups("habit")
     * @ORM\Column(type="boolean")
     */
    private $completed;

    /**
     * @Groups("habit")
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @Groups("user")
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="habits")
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

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->created_date;
    }

    public function setCreatedDate(\DateTimeInterface $created_date): self
    {
        $this->created_date = $created_date;

        return $this;
    }

    public function getModifiedDate(): ?\DateTimeInterface
    {
        return $this->modified_date;
    }

    public function setModifiedDate(\DateTimeInterface $modified_date): self
    {
        $this->modified_date = $modified_date;

        return $this;
    }

    public function getDayly()
    {
        return $this->dayly;
    }

    public function setDayly($dayly)
    {
        $this->dayly = $dayly;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCompleted()
    {
        return $this->completed;
    }

    public function setCompleted($completed): self
    {
        $this->completed = $completed;

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
