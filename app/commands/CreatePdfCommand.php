<?php
namespace App\Commands;

use App\Cheatsheets\ICheatsheetRepository;
use App\Cheatsheets\PdfGenerator;
use App\PdfException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CreatePdfCommand extends Command
{

	/** @var \App\Cheatsheets\ICheatsheetRepository */
	protected $cheatsheetRepository;

	/** @var \App\Cheatsheets\PdfGenerator */
	protected $pdfGenerator;


	/**
	 * @param PdfGenerator $pdfGenerator
	 * @param ICheatsheetRepository $cheatsheetRepository
	 */
	public function __construct(PdfGenerator $pdfGenerator, ICheatsheetRepository $cheatsheetRepository)
	{
		parent::__construct();
		$this->cheatsheetRepository = $cheatsheetRepository;
		$this->pdfGenerator = $pdfGenerator;
	}


	protected function configure()
	{
		$this->setName('app:createPdf');
	}


	public function execute(InputInterface $input, OutputInterface $output)
	{

		$locales = $this->cheatsheetRepository->getLocales();
		foreach ($locales as $locale) {
			$cheatsheets = $this->cheatsheetRepository->getCheatsheets($locale);
			foreach ($cheatsheets as $cheatsheet) {
				try {
					$output->writeln("Generating pdf for cheatsheet {$cheatsheet->getLocale()}/{$cheatsheet->getIdentifier()}");
					$filename = $this->pdfGenerator->generate($cheatsheet);
					$output->writeln("PDF saved in $filename");
				} catch (PdfException $e) {
					$output->writeln($e->getMessage());
				}
			}
		}
	}

}