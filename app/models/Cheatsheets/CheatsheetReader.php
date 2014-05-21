<?php
namespace App\Cheatsheets;

use App\Utils\Dom;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Nette\Diagnostics\Debugger;
use Nette\Object;

/**
 * @author David Matejka
 */
class CheatsheetReader extends Object
{

	/** @var \App\Cheatsheets\CheatsheetPathFormatter */
	protected $pathFormatter;

	/** @var \Nette\Caching\Cache */
	protected $cache;


	/**
	 * @param CheatsheetPathFormatter $pathFormatter
	 */
	public function __construct(CheatsheetPathFormatter $pathFormatter, IStorage $cacheStorage)
	{
		$this->pathFormatter = $pathFormatter;
		$this->cache = new Cache($cacheStorage, 'Cheatsheets');
	}


	public function readTitle($locale, $identifier)
	{
		$key = array('title', $locale, $identifier);
		if (!$title = $this->cache->load($key)) {
			$filename = $this->pathFormatter->format($locale, $identifier);
			if (($title = $this->parseTitle($filename)) === NULL) {
				$title = $identifier;
			}
			$this->cache->save($key, $title, array(
				Cache::EXPIRATION => '+6hours',
				Cache::TAGS       => array('cheatsheets'),
			));
		}

		return $title;
	}


	public function readContent($locale, $identifier)
	{
		$filename = $this->pathFormatter->format($locale, $identifier);
		$content = file_get_contents($filename);
		$footer = file_get_contents(sprintf('%s/footer.html', $this->pathFormatter->getBaseDirectory()));
		$content = str_replace('{footer}', $footer, $content);

		return $content;
	}


	protected function parseTitle($filename)
	{
		try {
			$domDocument = Dom::loadHtmlFile($filename);
			$domXpath = new \DOMXPath($domDocument);
			if ($h1Node = $domXpath->query('//h1')->item(0)) {
				return $h1Node->nodeValue;
			}
		} catch (\DOMException $e) {
			Debugger::log($e->getMessage(), 'dom');
		}

		return NULL;
	}
}