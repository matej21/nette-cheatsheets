<?php
namespace App\Cheatsheets;

/**
 * @author David Matejka
 */
interface ICheatsheetRepository
{

	/**
	 * @return array of locales
	 */
	public function getLocales();

	/**
	 * @param string $locale
	 * @return Cheatsheet[]
	 */
	public function getCheatsheets($locale = NULL);


	/**
	 * @param string $locale
	 * @param string $identifier
	 * @return Cheatsheet
	 */
	public function getCheatsheet($locale, $identifier);

}