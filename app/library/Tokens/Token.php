<?php

namespace Library\Tokens;

use DateTimeImmutable;
use DateTimeInterface;

class Token
{
    const AUDIENCE        = "aud";
    const EXPIRATION_TIME = "exp";
    const ID              = "jti";
    const ISSUED_AT       = "iat";
    const ISSUER          = "iss";
    const NOT_BEFORE      = "nbf";
    const SUBJECT         = "sub";

    private array $claims;

    public function __construct(array $claims = [])
    {
        $this->claims = $claims;
    }

    public function setAudience(string $audience) : Token
    {
        $this->claims[self::AUDIENCE] = $audience;
        return $this;
    }
    
    public function getAudience() : ?string
    {
        if ($this->hasClaim(self::AUDIENCE)) {
            
            return $this->claims[self::AUDIENCE];
        }

        return null;
    }

    public function setExpire(DateTimeInterface $expire) : Token
    {
        $this->claims[self::EXPIRATION_TIME] = $expire->getTimestamp();
        return $this;
    }

    public function getExpire() : ?DateTimeInterface
    {
        if ($this->hasClaim(self::EXPIRATION_TIME)) {
            
            return new DateTimeImmutable("@{$this->claims[self::EXPIRATION_TIME]}");
        }

        return null;
    }

    public function setId(string $id) : Token
    {
        $this->claims[self::ID] = $id;
        return $this;
    }

    public function getId() : ?string
    {
        if ($this->hasClaim(self::ID)) {
            
            return $this->claims[self::ID];
        }

        return null;
    }

    public function setIssuedAt(DateTimeInterface $issued) : Token
    {
        $this->claims[self::ISSUED_AT] = $issued->getTimestamp();
        return $this;
    }

    public function getIssuedAt() : ?DateTimeInterface
    {
        if ($this->hasClaim(self::ISSUED_AT)) {
            
            return new DateTimeImmutable("@{$this->claims[self::ISSUED_AT]}");
        }

        return null;
    }

    public function setIssuer(string $issuer) : Token
    {
        $this->claims[self::ISSUER] = $issuer;
        return $this;
    }

    public function getIssuer() : ?string
    {
        if ($this->hasClaim(self::ISSUER)) {
            
            return $this->claims[self::ISSUER];
        }

        return null;
    }

    public function setNotBefore(DateTimeInterface $nbf) : Token
    {
        $this->claims[self::NOT_BEFORE] = $nbf->getTimestamp();
        return $this;
    }

    public function getNotBefore() : ?DateTimeInterface
    {
        if ($this->hasClaim(self::NOT_BEFORE)) {
            
            return new DateTimeImmutable("@{$this->claims[self::NOT_BEFORE]}");
        }

        return null;
    }

    public function setSubject(string $subject) : Token
    {
        $this->claims[self::SUBJECT] = $subject;
        return $this;
    }
    
    public function getSubject() : ?string
    {
        if ($this->hasClaim(self::SUBJECT)) {
            
            return $this->claims[self::SUBJECT];
        }

        return null;
    }

    protected function addClaim(string $name, string $value) : Token
    {
        $this->claims[$name] = $value;
        return $this;
    }

    protected function getClaim(string $name) : string
    {
        return $this->claims[$name];
    }

    protected function hasClaim(string $name) : bool
    {
        return isset($this->claims[$name]) === true;
    }

    public function getClaims()
    {
        return $this->claims;
    }

}
