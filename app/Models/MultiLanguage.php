<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Lang;

class MultiLanguage extends Model
{
     protected $table = 'multilanguage';
	 
	public static function getDefaultLangId()
	{
		$config_global = config('settings.global');

		return $config_global['default_lang_id'];

	}

	public static function getCurrentLangId()
	{
		$config_global = config('settings.global');

		$default_lang_id = $config_global['default_lang_id'];
		$default_lang_code = $config_global['default_lang'];

		$locale = Lang::getLocale();

		if($default_lang_code == $locale)
		{
			return $default_lang_id;
		}
		else
		{
			$languages = self::all();
			
			foreach($languages as $language)
			{
				if($locale == $language->code)
				{
					$default_lang_id = $language->id;
					break;
				}
			}
			
			return $default_lang_id;
		}
	}
}
