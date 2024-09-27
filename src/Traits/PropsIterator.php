<?php

namespace Kyivstar\Api\Traits;

trait PropsIterator
{
    public array $props;

    function rewind() {
        return reset($this->props);
    }

    function current() {
        return current($this->props);
    }

    function key() {
        return key($this->props);
    }

    function next() {
        return next($this->props);
    }

    function valid(): bool
    {
        return key($this->props) !== null;
    }
}
