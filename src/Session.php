<?php

namespace Pipe;

class Session
{
    public $name = "pipesess";
    public $lifetime = 0;

    public function start(): self
    {
        if ($this->started()) {
            return $this;
        }

        $this->name($this->name);
        $this->lifetime($this->lifetime);
        session_start();
        return $this;
    }

    public function name(string $name): self
    {
        $this->name = $name;
        session_name($this->name);
        return $this;
    }

    public function lifetime(int $lifetime = 0): self
    {
        $this->lifetime = $lifetime;
        session_set_cookie_params($this->lifetime);
        return $this;
    }

    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, $value): self
    {
        $_SESSION[$key] = $value;
        return $this;
    }

    public function pluck(string $key, $default = null)
    {
        $v = $_SESSION[$key] ?? $default;
        $this->delete($key);
        return $v;
    }

    public function delete(string $key): self
    {
        unset($_SESSION[$key]);
        return $this;
    }

    public function clear()
    {
        $_SESSION = [];
        return $this;
    }

    public function id(bool $new = false)
    {
        if ($new && session_id()) {
            session_regenerate_id(true);
        }

        return session_id();
    }

    public function destroy(): self
    {
        if (!$this->id()) {
            return $this;
        }
        session_unset();
        session_destroy();
        session_write_close();
        return $this;
    }

    public function started(): bool
    {
        return isset($_SESSION) && session_id();
    }
}
