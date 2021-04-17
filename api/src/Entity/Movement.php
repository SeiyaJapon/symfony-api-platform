<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Symfony\Component\Uid\Uuid;

class Movement
{
    private string $id;
    private Category $category;
    private User $owner;
    private ?Group $group;
    private float $amount;
    private ?string $filePath;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    /**
     * Movement constructor.
     *
     * @param Category   $category
     * @param User       $owner
     * @param float      $amount
     * @param Group|null $group
     */
    public function __construct(Category $category, User $owner, float $amount, Group $group = null)
    {
        $this->id = Uuid::v4()->toRfc4122();
        $this->category = $category;
        $this->owner = $owner;
        $this->group = $group;
        $this->amount = $amount;
        $this->filePath = null;
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
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;
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
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string|null
     */
    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    /**
     * @param string|null $filePath
     */
    public function setFilePath(?string $filePath): void
    {
        $this->filePath = $filePath;
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

    /**
     * @param DateTime $updatedAt
     */
    public function markAsUpdated(): void
    {
        $this->updatedAt = new DateTime();
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->owner->getId() === $user->getId();
    }

    public function belongsToGroup(Group $group): bool
    {
        return (null === $this->group) ? false : $this->group->getId() === $group->getId();
    }
}