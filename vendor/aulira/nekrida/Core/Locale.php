<?php

namespace Nekrida\Core;

/**
 * 
 */
abstract class Locale
{
	/** @var Request */
	protected static $request;
	/** @var string */
	protected static $cookieLangParam;
	/** @var string */
	protected static $sessionLangParam;
	/** @var string */
	protected static $defaultLocale;

	protected static $localeNameTemplate;

	protected static $locale;

	public static function init(Request $request, $cookieLangParam = 'site_lang', $sessionLangParam = 'lang',$defaultLocale = 'ru') {
		self::$request = $request;
		self::$cookieLangParam = $cookieLangParam;
		self::$sessionLangParam = $sessionLangParam;
		self::$defaultLocale = $defaultLocale;

		self::$localeNameTemplate = str_replace('{root}',Config::dir(),Config::get('config/localeNameTemplate'));
	}

	/** 
	 * Translates the text to the necessary locale
	 * @abstract
	 * @param mixed $text
	 * @return mixed
	 */
	abstract public static function localize($text);

    /**
     * @return mixed
     */
    public static function getLocale()
    {
        return self::$locale;
    }

    /**
     * @param mixed $locale
     */
    public static function setLocale($locale)
    {
        self::$locale = $locale;
    }

    protected static function getLangPack() {
        //Check the current locale
        if (!is_null(self::$locale)) {
            $lang = self::loadLangPack(self::$locale);
            if ($lang) return $lang;
        }
		//Check session for the locale name
		if (!is_null(self::$request->session(self::$sessionLangParam))) {
			$lang = self::loadLangPack(self::$request->session(self::$sessionLangParam));
			if ($lang) return $lang;
		}
		//Else Check cookies for the locale name
		if (!is_null(self::$request->cookie(self::$cookieLangParam))) {
			$lang = self::loadLangPack(self::$request->cookie(self::$cookieLangParam));
			if ($lang) return $lang;
		}
		//Else check clients headers
		$langList = self::detectClientLanguage();
		if (!empty($langList))
			foreach ($langList as $key => $value) {
				$lang = self::loadLangPack($key);
				if ($lang) return $lang;
			}

		//If we still can't get a locale, return default one
		return self::loadLangPack(self::$defaultLocale);
	}

	/**
	 * Returns LangPack by requested locale
	 * @param $locale
	 * @return array|false LangPack
	 */
	protected static function loadLangPack($locale) {
		$lang = @include(str_replace('{lang}', $locale, self::$localeNameTemplate));
		return $lang;
	}

	protected static function detectClientLanguage() {
		if (!is_null(self::$request->server('HTTP_ACCEPT_LANGUAGE'))) {
			// break up string into pieces (languages and q factors)
			preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})?)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', self::$request->server('HTTP_ACCEPT_LANGUAGE'), $lang_parse);
			if (count($lang_parse[1])) {
				// create a list like "en" => 0.8
				$languages = array_combine($lang_parse[1], $lang_parse[4]);
				// set default to 1 for any without q factor
				foreach ($languages as $lang => $val)
					if ($val === '') $languages[$lang] = 1;
				// sort list based on value
				arsort($languages, SORT_NUMERIC);
				return $languages;
			}
		}
		return [];
	}
}