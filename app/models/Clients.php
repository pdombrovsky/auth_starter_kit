<?php

namespace App\Models;

use App\Models\Model;
use App\Models\Sessions;

use Phalcon\Di;

use DateTimeImmutable;
use DateTimeInterface;

class Clients extends Model
{
    protected ?int $id = null;
    protected string $finger_print;
    protected int $accessible;
    protected string $created;
    protected string $modified;

    public function getId() : ?int
    {
        return $this->id;
    }

    public function setFingerPrint(string $fingerPrint) : Clients
    {
        $this->finger_print = $fingerPrint;
        return $this;
    }

    public function getFingerPrint() : string
    {
        return $this->finger_print;
    }

    public function setAccessible(bool $accessible) : Clients
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

    public function closeOpenSessions() : bool
    {
        $sessions = $this->getOpenSessions();

        if ($sessions === null) {
            return false;
        }

        foreach($sessions as $session) {
            $session->close();
        }
        
        return true;
    }
    
    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        parent::initialize();
        $this->setSource('clients');
        $this->hasMany(
            'id',
            Sessions::class,
            'client_id',
            [
                'reusable' => true,
                'alias' => 'Sessions'
            ]
        );

        $this->hasMany(
            'id',
            Sessions::class,
            'client_id',
            [
                'reusable' => true,
                'alias' => 'OpenSessions',
                'params' => [
                    'conditions' => "accessible = 1",
                ],
            ]
        );
    }

    public static function createModel(string $fingerPrint) : Clients
    {
        $client = new Clients();
        $client
        ->setFingerPrint($fingerPrint)
        ->setAccessible(true)
        ->save();
        return $client;
    }

    public static function createFingerPrint() : string
    {
        return DI::getDefault()->getRandom()->base58(60);
    }
}
