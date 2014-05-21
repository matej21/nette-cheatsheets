<?php
namespace App\Cheatsheets;

use Nette\Object;
use Nette\Utils\Html;

/**
 * @author David Matejka
 */
class Cheatsheet extends Object
{

	/** @var string */
	protected $locale;

	/** @var string */
	protected $identifier;

	/** @var string */
	protected $title;

	/** @var string */
	protected $content;

	/** @var \App\Cheatsheets\CheatsheetReader */
	protected $cheatsheetReader;

	/**
	 * @param string $locale
	 * @param string $identifier
	 */
	public function __construct($locale, $identifier, CheatsheetReader $cheatsheetReader)
	{
		$this->locale = $locale;
		$this->identifier = $identifier;
		$this->cheatsheetReader = $cheatsheetReader;
	}


	/**
	 * @return Html
	 */
	public function getContent()
	{
		if($this->content === NULL) {
			$this->content = $this->cheatsheetReader->readContent($this->locale, $this->identifier);
		}

		return Html::el()->setHtml($this->content);
	}


	/**
	 * @return string
	 */
	public function getIdentifier()
	{
		return $this->identifier;
	}


	/**
	 * @return string
	 */
	public function getLocale()
	{
		return $this->locale;
	}


	/**
	 * @return string
	 */
	public function getTitle()
	{
		if ($this->title === NULL) {
			$this->title = $this->cheatsheetReader->readTitle($this->locale, $this->identifier);
		}

		return $this->title;
	}

}
