## Laravel Setting

### Installation:
```
composer require twom/laravel-setting
```

You must add the service provider to `config/app.php`
``` php
'providers' => [
	 // for laravel 5.8 and below
	 \Twom\Taggable\SettingServiceProvider::class,
];
```

## Config:
> config/setting.php
``` php
return [  
  /**  
   * drivers to save setting 
   * 
   * can be 'database', ... 
   */  
   "driver" => env("SETTING_DRIVER", "database"),  
  
  /**  
   * drivers list 
   */  
   "drivers" => [  
	  "database" => [  
		  "provider" => \Twom\Setting\src\Drivers\DB::class,  
		 ],  // json => [ provider => .... ]  
	  ],  
  
  /**  
    * statics settings 
    */  
    "settings" => [  
	  "app_name" => "laravel",  
	  "developer" => "ali ghale :)",  
	 ],  
  
  /**  
   * static defaults settings 
   */  
   "default" => [  
	  "another" => "does not have :("  
	],  
];
```


## Use in normalize mode:
```php
//	Single item
Setting::set("key", "value");
Setting::get("key", "default_value");
Setting::forget("key");

//	Multiple items
Setting::set(["one" => "value", "some" => "any..."]);
Setting::get(["one", "some"]);
Setting::forget(["key", "key-2"]);

Setting::all();
```

> **Note:** `default` can be `null` and is optional.

## Nested:
```php
// string
Setting::set("profile.notification.email", true);

// array
Setting::set([
	"profile" => [
		"notification" => [
			"sms" => true // or any value
		]
	], 
]);

//	get string
Setting::get("profile.notification.email", "default");

//	result should be an array
Setting::get("profile");
```

## Use Object mode:

#### Your settingable model:
> **Note:** should be use the **Settingable** trait from `Twom\Setting\Support\Settingable`
```php  
namespace App;  
  
use Illuminate\Database\Eloquent\Model;  
use Twom\Setting\Support\Settingable;  
  
class Post extends Model  
{  
  use Settingable;  
  
  public $timestamps = false;  
  
  protected $fillable = [  
	  'title', // and another fields
  ];  
}
```
### set and get:
```php
$post = Post::find(1);

//	Just like normaliza
$post->setSetting("key", "value");
$post->getSetting("key", "default");
$post->forgetSetting("key");
$post->getAllSetting();
```
