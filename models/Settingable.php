<?php


namespace Twom\Setting\models;


use Illuminate\Database\Eloquent\Model;

class Settingable extends Model
{
    protected $table = "twom_settingable";
    protected $fillable = ["id", "key", "value", "settingable_id", "settingable_type"];

    public function settingable()
    {
        return $this->morphTo();
    }
}
