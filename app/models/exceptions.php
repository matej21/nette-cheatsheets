<?php
namespace App;


class InvalidStateException extends \RuntimeException
{

}

class ModelException extends InvalidStateException
{

}

class CheatsheetException extends ModelException
{

}

class PdfException extends InvalidStateException
{

}

class FileNotFoundException extends \RuntimeException
{

}