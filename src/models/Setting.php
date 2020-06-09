<?php


namespace Twom\Setting\models;


use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = "twom_setting";
    protected $fillable = ["id", "key", "value"];
}
