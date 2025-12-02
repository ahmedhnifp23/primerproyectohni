<?php


class User
{
    

    private ?int $user_id;
    private string $first_name;
    private ?string $last_name;
    private string $email;
    private string $username;
    private string $password_hash;
    private ?string $phone;
    private ?array $addresses;
    private ?string $birth_date;
    private string $registered_at;
    private bool $is_admin;



    public function __construct(?int $user_id = null, string $first_name, ?string $last_name = null, string $email, string $username, string $password_hash, ?string $phone = null, ?array $addresses = null, ?string $birth_date = null, string $registered_at = null, bool $is_admin = false)
    {
        $this->user_id = $user_id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->username = $username;
        $this->password_hash = $password_hash;
        $this->phone = $phone;
        $this->addresses = $addresses;
        $this->birth_date = $birth_date;
        $this->registered_at = $registered_at;
        $this->is_admin = $is_admin;
    }

    //Function that returns an array with public properties to use it in json_encode. Returns an associative array with public properties.
    public function jsonSerialize(): array
    {
        return [
            'user_id' => $this->getUserId(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'email' => $this->getEmail(),
            'username' => $this->getUsername(),
            'password_hash' => $this->getPasswordHash(),
            'phone' => $this->getPhone(),
            'addresses' => $this->getAddresses(),
            'birth_date' => $this->getBirthDate(),
            'registered_at' => $this->getRegisteredAt(),
            'is_admin' => $this->getIsAdmin()
        ];
    }
    
    // Getters and setters
    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): void
    {
        $this->first_name = $first_name;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): void
    {
        $this->last_name = $last_name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getPasswordHash(): string
    {
        return $this->password_hash;
    }

    public function setPasswordHash(string $password_hash): void
    {
        $this->password_hash = $password_hash;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getAddresses(): ?array
    {
        return $this->addresses;
    }

    public function setAddresses(?array $addresses): void
    {
        $this->addresses = $addresses;
    }

    public function getBirthDate(): ?string
    {
        return $this->birth_date;
    }

    public function setBirthDate(?string $birth_date): void
    {
        $this->birth_date = $birth_date;
    }

    public function getRegisteredAt(): string
    {
        return $this->registered_at;
    }

    public function setRegisteredAt(string $registered_at): void
    {
        $this->registered_at = $registered_at;
    }

    public function getIsAdmin(): bool
    {
        return $this->is_admin;
    }

    public function setIsAdmin(bool $is_admin): void
    {
        $this->is_admin = $is_admin;
    }

}
