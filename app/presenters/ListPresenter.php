<?php
namespace App\Presenters;

use Nette\Application\UI\Presenter;

/**
 * @author David Matejka
 */
class ListPresenter extends Presenter
{
	/** @var \App\Cheatsheets\ICheatsheetRepository @inject */
	public $cheatsheetRepository;

	/** @var \WebLoader\LoaderFactory @inject */
	public $webLoader;


	public function startup()
	{
		parent::startup();
	}


	public function renderDefault()
	{
		$this->template->locales = $locales = $this->cheatsheetRepository->getLocales();
		$cheatsheets = array();
		foreach($locales as $locale) {
			$cheatsheets[$locale] = $this->cheatsheetRepository->getCheatsheets($locale);
		}
		$this->template->cheatsheets = $cheatsheets;
	}


	protected function createComponentCss()
	{
		return $this->webLoader->createCssLoader('web');
	}
}