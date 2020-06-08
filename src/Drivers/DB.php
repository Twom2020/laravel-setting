<?php


namespace Twom\Setting\src\Drivers;


use Illuminate\Database\Eloquent\Builder;
use Twom\Setting\src\Support\Driver;
use Twom\Setting\models\Setting as SettingModel;
use Twom\Setting\src\Support\Arr as SettArr;

class DB extends Driver
{
    /**
     * @inheritDoc
     */
    public function getSetting($key, $default = null)
    {
        $response = $this->newGetQuery($key)->first();

        if (!$response) {
            $response = $this->newGetQuery($key, true)->get();
            if ($count = count($response)) {
                if ($count > 1) {
                    $array = $response->toArray();
                    $array = array_combine(array_column($array, "key"), array_column($array, "value"));
                    $array = SettArr::toArray($array, static::getDefault($key, $default));
                    return array_shift($array);
                } else {
                    $response = $response->first();
                }
            }
        }

        return $response && $response->value ? $response->value : static::getDefault($key, $default);
    }


    /**
     * @inheritDoc
     */
    public function setSetting($key, $value = null)
    {
        if (is_null($value))
            throw new \Exception("set a value for {$key} setting");

        return (boolean)$this->newQuery()->updateOrInsert(['key' => $key], [
            "key"   => $key,
            "value" => $value,
        ]);
    }


    /**
     * @inheritDoc
     */
    public function forgetSetting($key)
    {
        return (boolean)$this->newQuery()
            ->where("key", $key)
            ->orWhere("key", "like", $key . ".%")
            ->delete();
    }


    /**
     * @inheritDoc
     */
    public function getAll()
    {
        $response = $this->newGetQuery()->get();
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
    public function newQuery()
    {
        return SettingModel::query();
    }


    /**
     * @param null $key
     * @param bool $like
     * @return Builder
     */
    public function newGetQuery($key = null, $like = false)
    {
        $query = $this->newQuery();
        if (!is_null($key)) {
            if ($like)
                $query->where("key", "like", $key . "%");
            else
                $query->where("key", $key);
        }
        return $query;
    }
}
