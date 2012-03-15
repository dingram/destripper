<?php
require 'destrip.php';

class AnnotationService
{
	protected static $words = array('copper');

	protected static function findAnnotations($in, $word)
	{
		$annotations = array();
		$an = 0;
		do {
			$an = stripos($in, $word, $an);
			if ($an !== false) {
				$annotations[$an] = $word;
			}
			++$an;
		} while ($an !== false);
		return $annotations;
	}

	public static function call($in)
	{
		$annotations = array();
		foreach (self::$words as $word) {
			$annotations += self::findAnnotations($in, $word);
		}
		return $annotations;
	}
}

#var_dump(AnnotationService::call('copper'));
#var_dump(AnnotationService::call('copper copper cooper capper copper cop per coppe'));

function test_destripper($in) {
	var_dump($in);
	$d = new Destripper($in);
	var_dump($d->getStripped());
	$annotations = AnnotationService::call($d->getStripped());
	var_dump('-----------------------------------');
	var_dump($d->getDestripped());
	var_dump('-----------------------------------');
	var_dump($d->applyAnnotations($annotations));
	var_dump('===================================');
}

#test_destripper('abcdef<g>hi');
#test_destripper('abc<d>ef<g>hi');
#test_destripper('abc<b>def</b>ghi');
#test_destripper('This is copper sulphate');
test_destripper('This is <b>cop</b>per sulphate');
test_destripper('This is <b>copper</b> sulphate');
test_destripper('This is <b>copper sulphate</b>');

/*
#$stripped = $output;

$annotations = array();
 */
