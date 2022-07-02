<?php

namespace App\Models;

use App\Models\Model;
use App\Models\Sessions;

use DateTimeImmutable;
use DateTimeInterface;
use Phalcon\DI;

class Users extends Model
{
    protected ?int $id = null;
    protected string $uuid;
    protected string $first_name;
    protected string $last_name;
    protected string $email;
    protected string $password_hash;
    protected string $valid_through;
    protected int $active;
    protected ?string $previous_password_hash = null;
    protected string $created;
    protected string $modified;

    public function getId() : ?int
    {
        return $this->id;
    }

    public function setUuid(string $uId) : Users
    {
        $this->uuid = $uId;
        return $this;
    }

    public function getUuid() : string
    {
        return $this->uuid;
    }

    public function setFirstName(string $firstName) : Users
    {
        $this->first_name = $firstName;
        return $this;
    }

    public function getFirstName() : string
    {
        return $this->first_name;
    }

    public function setLastName(string $lastName) : Users
    {
        $this->last_name = $lastName;
        return $this;
    }

    public function getLastName() : string
    {
        return $this->last_name;
    }

    public function setEmail(string $email) : Users
    {
        $this->email = $email;
        return $this;
    }

    public function getEmail() : string
    {
        return $this->email;
    }

    public function setPasswordHash(string $passwordHash) : Users
    {
        $this->password_hash = $passwordHash;
        return $this;
    }

    public function getPasswordHash() : string
    {
        return $this->password_hash;
    }

    public function setValidThrough(DateTimeInterface $validThrough) : Users
    {
        $this->valid_through = $validThrough->format(self::$format);
        return $this;
    }

    public function getValidThrough() : DateTimeInterface
    {
        return new DateTimeImmutable($this->valid_through);
    }

    public function setActive(bool $active) : Users
    {
        $this->active = (int) $active;
        return $this;
    }

    public function getActive() : bool
    {
        return (bool) $this->active;
    }

    public function setPreviousPasswordHash(?string $previousPasswordHash) : Users
    {
        $this->previous_password_hash = $previousPasswordHash;
        return $this;
    }

    public function getPreviousPasswordHash() : ?string
    {
        return $this->previous_password_hash;
    }

    public function getCreated() : DateTimeInterface
    {
        return new DateTimeImmutable($this->created);
    }

    public function getModified() : DateTimeInterface
    {
        return new DateTimeImmutable($this->modified);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->setSource('users');
        $this->hasMany(
            'uuid',
            Sessions::class,
            'user_uuid',
            [
                'reusable' => true,
                'alias' => 'Sessions'
            ]
        );
    }

    public function validatePassword(string $password) : bool
    {
        return $this->di->getSecurity()->checkHash($password, $this->getPasswordHash());
    }

    public static function hashPassword(string $password)
    {
        $security = DI::getDefault()->getSecurity();
        return $security->hash($password);
    }
}
