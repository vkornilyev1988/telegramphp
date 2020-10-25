<?php

namespace Nekrida\Locale;

use Nekrida\Core\View as CoreView;

class View extends CoreView {
	
	public static function postRender($page) {
			return Legacy::localize($page);
	}
}