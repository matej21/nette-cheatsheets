<?php
namespace App\Utils;

use Nette\Object;

class Dom extends Object
{

	public static function loadHtmlFile($filename)
	{
		return self::loadHtml(file_get_contents($filename));
	}

	public static function loadHtml($content)
	{
		$domDoc = new \DOMDocument();
		libxml_clear_errors();
		libxml_use_internal_errors(TRUE);
		if (!$domDoc->loadHTML('<?xml encoding="UTF-8">' . $content)) {
			$errors = implode(', ', libxml_get_errors());
			libxml_clear_errors();
			throw new \DomException("Unable to parse DOM: " . $errors);
		}
		libxml_use_internal_errors(FALSE);
		$domDoc->encoding = 'UTF-8';

		return $domDoc;

	}
}

