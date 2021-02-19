<?php

declare(strict_types=1);

namespace App\Messenger\Message;

class UserRegisteredMessage
{
    private string $id;
    private string $name;
    private string $email;
    private string $token;

    public function __construct(string $id, string $name, string $email, string $token)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function email(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function token(): string
    {
        return $this->token;
    }

}