<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Symfony\Component\Uid\Uuid;

class Category
{
    public const EXPENSE = 'expense';
    public const INCOME = 'income';

    private string $id;
    private string $name;
    private string $type;
    private User $owner;
    private ?Group $group;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    /**
     * Category constructor.
     *
     * @param string     $name
     * @param string     $type
     * @param User       $owner
     * @param Group|null $group
     */
    public function __construct(string $name, string $type, User $owner, Group $group = null)
    {
        $this->id = Uuid::v4()->toRfc4122();
        $this->name = $name;
        $this->type = $type;
        $this->owner = $owner;
        $this->group = $group;
        $this->createdAt = new DateTime();
        $this->markAsUpdated();
    }

    /**
     * @return string
     */
    public function getId(): string
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
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return User
     */
    public function getOwner(): User
    {
        return $this->owner;
    }

    /**
     * @return Group|null
     */
    public function getGroup(): ?Group
    {
        return $this->group;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    public function markAsUpdated(): void
    {
        $this->updatedAt = new DateTime();
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->owner->getId() === $user->getId();
    }
}