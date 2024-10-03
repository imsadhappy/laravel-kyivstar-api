<?php

namespace Kyivstar\Api\Traits;

trait ObjectToArray
{
    public function toArray(): array
    {
        $props = get_object_vars($this);

        foreach ($props as $key => $prop) {
            if (is_object($prop) && method_exists($prop, 'toArray')) {
                $props[$key] = $prop->toArray();
            }
        }

        return $props;
    }
}
