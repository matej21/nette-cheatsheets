<?php
namespace App\Cheatsheets;

use App\Pdf;
use Nette\Object;
use Nette\Utils\FileSystem;
use WebLoader\LoaderFactory;

/**
 * @author David Matejka
 */
class PdfGenerator extends Object
{

	/** @var \App\Pdf\PdfGenerator */
	protected $pdfGenerator;

	/** @var \App\Cheatsheets\PdfPathFormatter */
	protected $pdfPathFormatter;

	/** @var \WebLoader\LoaderFactory */
	protected $webloader;


	public function __construct(Pdf\PdfGenerator $pdfGenerator,
		PdfPathFormatter $pdfPathFormatter,
		LoaderFactory $webloader)
	{
		$this->pdfPathFormatter = $pdfPathFormatter;
		$this->pdfGenerator = $pdfGenerator;
		$this->webloader = $webloader;
	}


	/**
	 * @param string $locale
	 * @param string $id
	 * @return string pdf filename
	 */
	public function generate(Cheatsheet $cheatsheet)
	{
		$outputFile = $this->getOutputFile($cheatsheet->getLocale(), $cheatsheet->getIdentifier());
		$html = $this->createHtml($this->getCss(), $cheatsheet->getContent());
		$this->pdfGenerator->createFromString($html, $outputFile, $this->getOptions());

		return $outputFile;
	}


	protected function createHtml($css, $content)
	{
		return sprintf('<!DOCTYPE html>
<html>
<head>
	<title>Cheatsheet</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<meta charset="UTF-8">
	<style type="text/css">
	%s
	</style>
</head>
<body>
%s
</body>
</html>', $css, $content);
	}


	/**
	 * @return array
	 */
	protected function getOptions()
	{
		$options = array(
			'viewport-size'             => '1200x1000',
			'orientation'               => 'landscape',
			'margin-left'               => '2mm',
			'margin-right'              => '2mm',
			'margin-bottom'             => '2mm',
			'margin-top'                => '2mm',
			'load-media-error-handling' => 'ignore',
		);

		return $options;
	}


	/**
	 * @return string
	 */
	private function getCss()
	{
		$loader = $this->webloader->createCssLoader('cheatsheets');
		$compiler = $loader->getCompiler();
		$css = $compiler->getContent();

		return $css;
	}


	/**
	 * @param $locale
	 * @param $id
	 * @return string
	 */
	private function getOutputFile($locale, $id)
	{
		$outputFile = $this->pdfPathFormatter->format($locale, $id);
		FileSystem::createDir(dirname($outputFile));

		return $outputFile;
	}
}