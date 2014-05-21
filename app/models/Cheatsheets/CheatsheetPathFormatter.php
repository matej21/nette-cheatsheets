<?php
namespace App\Cheatsheets;

use Nette\Object;

/**
 * @author David Matejka
 */
class CheatsheetPathFormatter extends Object
{

	/** @var string */
	protected $directory;


	/**
	 * @param string $directory
	 */
	public function __construct($directory)
	{
		$this->directory = $directory;
	}


	/**
	 * @param string $locale
	 * @param string $identifier
	 * @return string
	 */
	public function format($locale, $identifier)
	{
		return sprintf('%s/%s/%s.html', $this->directory, $locale, $identifier);
	}

	public function getBaseDirectory()
	{
		return $this->directory;
	}
}