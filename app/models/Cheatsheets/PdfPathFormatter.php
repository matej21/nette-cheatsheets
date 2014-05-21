<?php
namespace App\Cheatsheets;

use Nette\Object;

/**
 * @author David Matejka
 */
class PdfPathFormatter extends Object
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
	 * @param string $id
	 * @return string
	 */
	public function format($locale, $id)
	{
		return sprintf("%s/%s/%s.pdf", $this->directory, $locale, $id);
	}
}
