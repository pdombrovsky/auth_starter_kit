<?php

namespace Library\Http;

use Phalcon\Http\Response\Cookies;
use Phalcon\Config;

class Cookie
{
    protected array $options;
    protected string $name;
    protected Cookies $cookies;

    public function __construct(Config $config, Cookies $cookies)
    {
        $this->name = $config->prefix . $config->name;
        $this->options = $config->options->toArray();
        $this->cookies = $cookies;
    }

    public function get() : ?string
    {
        if ($this->cookies->has($this->name)) {
            $cookie = $this->cookies->get($this->name);
            return $cookie->getValue();
        }
        return null;
    }

    public function set(string $token, int $expires = 0) : void
    {
        $this->cookies->set(
            $this->name,
            $token,
            $expires,
            $this->options['path'],
            $this->options['secure'],
            $this->options['domain'],
            $this->options['httpOnly'],
            [
                'samesite' => $this->options['samesite']
            ]
        );
    }

    public function delete() : bool
    {
        if ($this->cookies->has($this->name)) {
            $cookie = $this->cookies->get($this->name);
            $cookie->delete();
            return true;
        }
        return false;
    }
}
