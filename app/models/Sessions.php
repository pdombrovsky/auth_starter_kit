<?php

namespace App\Models;

use App\Models\Model;
use App\Models\Clients;
use App\Models\Users;

use Phalcon\DI;

use DateTimeImmutable;
use DateTimeInterface;

class Sessions extends Model
{
    protected ?int $id = null;
    protected string $user_uuid;
    protected string $uuid;
    protected int $client_id;
    protected string $valid_through;
    protected int $accessible;
    protected string $created;
    protected string $modified;

    public function getId() : ?int
    {
        return $this->id;
    }

    public function setUserUuid(string $userUuid) : Sessions
    {
        $this->user_uuid = $userUuid;
        return $this;
    }

    public function getUserUuid() : string
    {
        return $this->user_uuid;
    }

    public function setUuid(string $uId) : Sessions
    {
        $this->uuid = $uId;
        return $this;
    }

    public function getUuid() : string
    {
        return $this->uuid;
    }

    public function setClientId(int $clientId) : Sessions
    {
        $this->client_id = $clientId;
        return $this;
    }

    public function getClientId() : int
    {
        return $this->client_id;
    }

    public function setValidThrough(DateTimeInterface $validThrough) : Sessions
    {
        $this->valid_through = $validThrough->format(self::$format);
        return $this;
    }

    public function getValidThrough() : DateTimeInterface
    {
        return new DateTimeImmutable($this->valid_through);
    }

    public function setAccessible(bool $accessible) : Sessions
    {
        $this->accessible = (int) $accessible;
        return $this;
    }

    public function getAccessible() : bool
    {
        return (bool) $this->accessible;
    }

    public function getCreated() : DateTimeInterface
    {
        return new DateTimeImmutable($this->created);
    }

    public function getModified() : DateTimeInterface
    {
        return new DateTimeImmutable($this->modified);
    }

    public function close() : Sessions  
    {
        $this
        ->setAccessible(false)
        ->save();
        return $this;
    }

    public function prolongate(string $newSessionUuid, DateTimeInterface $validThrough) : Sessions
    {
        $this
        ->setUuid($newSessionUuid)
        ->setValidThrough($validThrough)
        ->save();
        return $this;
    }
    
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->setSource('sessions');
        
        $this->belongsTo(
            'client_id',
            Clients::class,
            'id',
            [
                'reusable' => true,
                'alias' => 'Clients'
            ]
        );

        $this->belongsTo(
            'user_uuid',
            Users::class,
            'uuid',
            [
                'reusable' => true,
                'alias' => 'Users'
            ]
        );
    }

    public static function createModel(
        string $userUuid,
        int $clientId,
        DateTimeInterface $validThrough,
        string $uuid
    ) {
        $session = new Sessions();
        $session
        ->setUserUuid($userUuid)
        ->setClientId($clientId)
        ->setValidThrough($validThrough)
        ->setUuid($uuid)
        ->setAccessible(true)
        ->save();
        return $session;
    }
}
