<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Symfony\Component\Uid\Uuid;

class GroupRequest
{
    public const PENDING = 'pending';
    public const ACCEPTED = 'accepted';

    private string $id;
    private Group $group;
    private User $user;
    private string $token;
    private string $status;
    private ?DateTime $acceptedAt;

    /**
     * GroupRequest constructor.
     *
     * @param Group $group
     * @param User  $user
     */
    public function __construct(Group $group, User $user)
    {
        $this->id = Uuid::v4()->toRfc4122();
        $this->group = $group;
        $this->user = $user;
        $this->token = sha1(uniqid());
        $this->status = self::PENDING;
        $this->acceptedAt = null;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return Group
     */
    public function getGroup(): Group
    {
        return $this->group;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return DateTime|null
     */
    public function getAcceptedAt(): ?DateTime
    {
        return $this->acceptedAt;
    }

    /**
     * @param DateTime|null $acceptedAt
     */
    public function setAcceptedAt(?DateTime $acceptedAt): void
    {
        $this->acceptedAt = $acceptedAt;
    }
}