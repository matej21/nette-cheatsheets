<?php
namespace App\Pdf;

use App\FileNotFoundException;
use App\PdfException;
use Nette\Object;
use Nette\Utils\Strings;

/**
 * @author David Matejka
 */
class PdfGenerator extends Object
{

	/** @var string */
	protected $binary;


	/**
	 * @param string $wkhtmltopdfBinary
	 */
	public function __construct($wkhtmltopdfBinary)
	{
		$this->binary = $wkhtmltopdfBinary;
	}


	public function createFromFile($inputFile, $outputFile, array $options = array(), &$output = NULL)
	{
		if(!file_exists($inputFile)) {
			throw new FileNotFoundException("File $inputFile not found");
		}
		$command = $this->buildCommand($inputFile, $outputFile, $options);
		exec($command, $output, $exitCode);
		if (!$exitCode == 0) {
			throw new PdfException("Unable to generate PDF");
		}
	}


	public function createFromString($html, $outputFile, array $options = array(), &$output = NULL)
	{
		$tempFileName = sys_get_temp_dir() . '/wkhtmltopdf' . time() . Strings::random(5) . '.html';
		file_put_contents($tempFileName, $html);
		try {
			$this->createFromFile($tempFileName, $outputFile, $options, $output);
		} catch (PdfException $e) {
			unlink($tempFileName);
			throw $e;
		}
		unlink($tempFileName);
	}


	private function buildCommand($input, $output, $options = array())
	{
		$args = $this->binary;
		foreach ($options as $key => $value) {
			if ($value === NULL || $value === FALSE) {
				continue;
			}
			$dash = strlen($key) === 1 ? '-' : '--';
			if ($value === TRUE) {
				$args .= " {$dash}{$key} ";
			} else {
				$args .= " {$dash}{$key} " . escapeshellarg($value);
			}
		}
		$args .= ' ' . escapeshellarg($input) . ' ' . escapeshellarg($output);

		return $args;
	}
}