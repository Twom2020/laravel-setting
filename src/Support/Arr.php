<?php


namespace Twom\Setting\src\Support;


class Arr
{
    /**
     * @param $key
     * @return mixed|null
     */
    public static function getLatestKey($key)
    {
        if (is_string($key)) {
            if (strpos($key, '.') !== false) {
                $key = explode('.', (string)$key);
                return array_pop($key);
            }
        } else {
            return null;
        }
    }

    public static function toArray(array $input, $default = true)
    {
        $r = [];
        foreach ($input as $dotted => $value) {
            $keys = explode('.', $dotted);
            $c = &$r[array_shift($keys)];
            foreach ($keys as $key) {
                if (!isset($c[$key]) || !is_array($c[$key])) {
                    $c[$key] = [];
                }
                $c = &$c[$key];
            }
            $c = $value ?? $default;
        }
        return $r;
    }
}
