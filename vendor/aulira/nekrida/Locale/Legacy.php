<?php

namespace Nekrida\Locale;

use Nekrida\Core\Locale;

/**
 * 
 */
class Legacy extends Locale {

	const LOCALE_TEXT_PATTERN1 = "/\{\# (.*?) \{ (.*?) \} \#\}/mu";

	const LOCALE_TEXT_PATTERN2 = '/{\# (.*?) \#}/mu';

	/**
	 * Returns localized string
	 * @Todo Rewrite 2 $result-s in one function
	 * @param string $page
	 * @return string
	 */
	public static function localize($page) {
		$langPack = self::getLangPack();
		$result = preg_replace_callback(self::LOCALE_TEXT_PATTERN1,
			function ($match) use ($langPack) {
				$m = $match[1];
				if (isset($langPack[$m]) && !empty($langPack[$m]))
					$res = $langPack[$m];
				else
					$res = $m;
				foreach (explode('|', $match[2]) as $key => $arg) {
					$res = str_replace('$' . ($key + 1), $arg, $res);
				}
				return $res;
			}, $page);

		//Localize other phrases
		$result2 = preg_replace_callback(self::LOCALE_TEXT_PATTERN2,
			function ($match) use ($langPack) {
				$m = $match[1];
				if (isset($langPack[$m]) && !empty($langPack[$m]))
					$res = $langPack[$m];
				else
					$res = $m;
				return $res;
			}, $result);
		return $result2;

	}
}