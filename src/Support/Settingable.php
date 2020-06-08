<?php


namespace Twom\Setting\src\Support;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Twom\Setting\src\Facade\Setting;
use Twom\Setting\Models\Settingable as SettingableModel;
use Twom\Setting\src\Support\Arr as SettArr;

/**
 * Trait Settingable
 * @package Twom\Setting\src\Support
 */
trait Settingable
{
    /**
     * @return mixed
     */
    public function settings()
    {
        return $this->morphMany(SettingableModel::class, "settingable");
    }


    /**
     * @param $key
     * @param null $default
     * @return array|mixed|null
     */
    public function getSetting($key, $default = null)
    {
        $response = null;
        if (is_array($key)) {
            $response = [];
            foreach ($key as $index) {
                $get = $this->getSetting($index, $default);
                $index = (strpos($index, '.') !== false) ? SettArr::getLatestKey($index) : $index;
                $response[$index] = isset($response[$index]) && is_array($get)
                    ? array_merge($response[$index], $get)
                    : $get;
            }
            return $response;
        } else {
            $response = $this->newSettingGetQuery($key)->first();

            if (!$response) {
                $response = $this->newSettingGetQuery($key, true)->get();
                if ($count = count($response)) {
                    if ($count > 1) {
                        $array = $response->toArray();
                        $array = array_combine(array_column($array, "key"), array_column($array, "value"));
                        $array = SettArr::toArray($array, Setting::getDefault($key, $default));
                        return array_shift($array);
                    } else {
                        $response = $response->first();
                    }
                }
            }

            return $response && $response->value ? $response->value : Setting::getDefault($key, $default);
        }
    }


    /**
     * @param $key
     * @param null $value
     * @return array|bool
     * @throws \Exception
     */
    public function setSetting($key, $value = null)
    {
        if (is_array($key)) {
            $key = Arr::dot($key);
            $response = [];
            foreach ($key as $index => $item) {
                $response[$index] = $this->setSetting($index, $item);
            }
            return $response;
        }

        if (is_null($value))
            throw new \Exception("set a value for {$key} setting");

        return (boolean)$this->newSettingQuery()->updateOrInsert(['key' => $key], [
            "key"              => $key,
            "value"            => $value,
            "settingable_id"   => $this->id,
            "settingable_type" => static::class,
        ]);
    }


    public function forgetSetting($key)
    {
        if (is_array($key)) {
            $response = [];
            foreach ($key as $item) {
                $response[$item] = $this->forgetSetting($item);
            }
            return $response;
        }

        return (boolean)$this->newSettingQuery()
            ->where("key", $key)
            ->orWhere("key", "like", $key . ".%")
            ->delete();
    }


    public function getAllSetting()
    {
        $response = $this->newSettingGetQuery()->get();
        if ($count = count($response)) {
            $array = $response->toArray();
            $array = array_combine(array_column($array, "key"), array_column($array, "value"));
            return SettArr::toArray($array);
        }

        return $response;
    }


    /**
     * @return Builder
     */
    public function newSettingQuery()
    {
        return $this->settings();
    }


    /**
     * @param null $key
     * @param bool $like
     * @return Builder
     */
    public function newSettingGetQuery($key = null, $like = false)
    {
        $query = $this->newSettingQuery();
        if (!is_null($key)) {
            if ($like)
                $query->where("key", "like", $key . "%");
            else
                $query->where("key", $key);
        }
        return $query;
    }
}
