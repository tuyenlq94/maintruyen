<?php

namespace T_Shop\Support;

class Cache
{
    public static function remember(
        $key,
        $callback,
        $ttl = 3600
    ) {

        $cached = wp_cache_get($key);

        if ($cached !== false) {
            return $cached;
        }

        $data = call_user_func($callback);

        wp_cache_set($key, $data, '', $ttl);

        return $data;
    }
}