<?php

namespace Twom\Setting\src\Support;

use Illuminate\Config\Repository as RepositoryAlias;
use Illuminate\Support\Arr;
use Twom\Setting\src\Support\Arr as SettArr;

abstract class Driver
{
    /**
     * @param string $key
     * @param null $default
     * @return mixed
     * @throws \Exception
     */
    public function get(string $key, $default = null)
    {
        $response = null;
        if (is_array($key)) {
            $response = [];
            foreach ($key as $index) {
                $get = $this->get($index, $default);
                $index = SettArr::getLatestKey($index);
                $response[$index] = isset($response[$index]) && is_array($get)
                    ? array_merge($response[$index], $get)
                    : $get;
            }
            return $response;
        } else
            return $this->getSetting($key, $default);
    }


    /**
     * @param string $key
     * @param null $value
     * @return array
     * @throws \Exception
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            $key = Arr::dot($key);
            $response = [];
            foreach ($key as $index => $item) {
                $response[$index] = $this->setSetting($index, $item);
            }
            return $response;
        } else
            return $this->setSetting($key, $value);
    }


    /**
     * get all settings
     */
    public function all()
    {
        return $this->getAll();
    }


    /**
     * @param $key
     * @return mixed
     * @throws \Exception
     */
    public function forget($key)
    {
        if (is_array($key)) {
            $response = [];
            foreach ($key as $item) {
                $response[$item] = $this->forgetSetting($item);
            }
            return $response;
        }
        return $this->forgetSetting($key);
    }


    /**
     * @param $key
     * @param null $default
     * @return RepositoryAlias|mixed|null
     */
    public static function getDefault($key, $default = null)
    {
        return $default ?? config("setting.default.{$key}") ?? null;
    }


    /*****************************      Abstracts       *****************************/

    /**
     * @param string $key
     * @param null $default
     * @return mixed
     * @throws \Exception
     */
    abstract public function getSetting($key, $default = null);


    /**
     * @param $key
     * @param null $value
     * @return mixed
     * @throws \Exception
     */
    abstract public function setSetting($key, $value = null);


    /**
     * @param string $key
     * @return mixed
     */
    abstract public function forgetSetting($key);


    /**
     * get all settings
     *
     * @return mixed
     */
    abstract public function getAll();
}
