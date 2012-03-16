<?php
require 'destrip.php';

class AnnotationService
{
	protected static $words = array('copper', 'sulphate');

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
	echo $in . "\n";
	$d = new Destripper($in);
	#var_dump($d->getStripped());
	$annotations = AnnotationService::call($d->getStripped());
	#echo "------------------------------------------------------------------------\n";
	#var_dump($d->getDestripped());
	#var_dump($d->getDestrippedDebug());
	echo "\n------------------------------------------------------------------------\n\n";
	echo $d->applyAnnotations($annotations) . "\n";
	echo "\n========================================================================\n\n";
}

#test_destripper('abcdef<g>hi');
#test_destripper('abc<d>ef<g>hi');
#test_destripper('abc<b>def</b>ghi');
#test_destripper('This is copper sulphate');

#test_destripper('This is <b>cop</b>per sulphate');
#test_destripper('This is <b>copper</b> sulphate');
#test_destripper('This is <b>copper sulphate</b>');
#test_destripper('<p>This is <b>copper sulphate</b></p>');
#test_destripper('<p>This is <b>copper sulphate</b>.</p>');

$test_cases = array(
	'<p>This is copper isn\'t it cool</p>',
	'<p>This is cop<b>per</b> isn\'t it cool</p>',
	'<p>This is <b>copper</b> isn\'t it cool</p>',
	'<p>This is <b>copper ruddy sulphate</b> isn\'t it cool</p>',
	'<p>This is <b>copper ruddy</b> sulphate isn\'t it cool</p>',
	'<p>This is copper <b>ruddy</b> sulphate isn\'t it cool</p>',
	'<p><s>This is copper <b>ruddy</b> sulphate isn\'t it cool</s></p>',
	'<p><s>This is copper <b>ruddy</b> sulphate<br/>isn\'t it cool</s></p>',
);

foreach ($test_cases as $test) {
	test_destripper($test);
}
