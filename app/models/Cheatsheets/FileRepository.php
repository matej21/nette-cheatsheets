<?php
namespace App\Cheatsheets;

use Nette\Object;
use Nette\Utils\Finder;

/**
 * @author David Matejka
 */
class FileRepository extends Object implements ICheatsheetRepository
{

	/** @var \App\Cheatsheets\CheatsheetPathFormatter */
	protected $pathFormatter;

	/** @var \App\Cheatsheets\CheatsheetReader */
	protected $reader;


	/**
	 * @param CheatsheetPathFormatter $pathFormatter
	 * @param CheatsheetReader $reader
	 */
	public function __construct(CheatsheetPathFormatter $pathFormatter, CheatsheetReader $reader)
	{
		$this->pathFormatter = $pathFormatter;
		$this->reader = $reader;
	}


	public function getCheatsheets($locale = NULL)
	{
		$result = array();
		/** @var \SplFileInfo $fileInfo */
		foreach (Finder::findFiles(($locale ? : '*') . '/*.html')->from($this->pathFormatter->getBaseDirectory()) as $filename => $fileInfo) {
			$identifier = pathinfo($filename, PATHINFO_FILENAME);
			$locale = basename(dirname($filename));

			$result[] = $this->getCheatsheet($locale, $identifier);
		}

		return $result;
	}


	public function getCheatsheet($locale, $identifier)
	{
		return new Cheatsheet($locale, $identifier, $this->reader);
	}


	/**
	 * @return array of locales
	 */
	public function getLocales()
	{
		$result = array();

		foreach (Finder::findDirectories("*")->in($this->pathFormatter->getBaseDirectory()) as $filename => $fileinfo) {
			$result[] = basename($filename);
		}

		return $result;
	}

}