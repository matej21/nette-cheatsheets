<?php
namespace App\Presenters;

use App\CheatsheetException;
use App\Cheatsheets\PdfPathFormatter;
use Nette\Application\Responses\FileResponse;
use Nette\Application\UI\Presenter;
use Nette\Utils\Html;

/**
 * @author David Matejka
 */
class DetailPresenter extends Presenter
{

	/** @var string @persistent */
	public $locale;

	/** @var string @persistent */
	public $id;

	/** @var \App\Cheatsheets\ICheatsheetRepository @inject */
	public $cheatsheetRepository;

	/** @var PdfPathFormatter @inject */
	public $pdfPathFormatter;

	/** @var \WebLoader\LoaderFactory @inject */
	public $webLoader;

	public function renderDefault($locale, $id)
	{
		try {
			$this->template->cheatsheet = $this->cheatsheetRepository->getCheatsheet($locale, $id);
		} catch(CheatsheetException $e) {
			$this->error($e->getMessage());
		}
		$this->template->locale = $locale;
	}

	public function actionPdf($locale, $id)
	{
		$this->sendResponse(new FileResponse($this->pdfPathFormatter->format($locale, $id), "nette-{$id}-{$locale}.pdf"));
	}


	protected function createComponentCss()
	{
		return $this->webLoader->createCssLoader('cheatsheetsWeb');
	}
}