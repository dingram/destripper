<?php

class Destripper
{
	protected $orig = null;
	protected $stripped = null;
	protected $removals = array();

	public function __construct($str)
	{
		$this->orig = $str;
	}

	protected function strip()
	{
		if ($this->stripped !== null) {
			return;
		}

		$removals = array();
		$r = '';

		# step 1: remove tags
		$c = 0;
		$o = $this->orig;
		$t = 0;
		$l = strlen($o);
		while ($c < $l) {
			$from = strpos($o, '<', $c);
			#var_dump($from);
			if ($from === false) break;
			$to = strpos($o, '>', $from);
			#var_dump($to);
			if ($to === false) break;
			++$to;
			$removals[$from - $t] = $to - $from;
			$t += ($to - $from);
			$p = substr($o, $c, $from-$c);
			$r .= $p;
			$c = $to;
		}
		$r .= substr($o, $c);
		#var_dump($removals);

		ksort($removals);
		$this->removals = $removals;
		$this->stripped = $r;
	}

	public function getStripped()
	{
		$this->strip();
		return $this->stripped;
	}

	public function getRemovals()
	{
		$this->strip();
		return $this->removals;
	}

	public function getDestripped()
	{
		$input = $this->stripped;
		$r = '';
		$c = 0;
		foreach ($this->removals as $pos => $count) {
			$r .= substr($this->stripped, $c, $pos - $c);
			$r .= str_repeat('*', $count);
			$c += ($pos - $c);
		}
		$r .= substr($this->stripped, $c);

		return $r;
	}

	public function applyAnnotations(array $annotations)
	{
		$input = $this->stripped;
		#ksort($annotations);
		$r = '';
		$c = 0;
		foreach ($annotations as $pos => $word) {
			if ($pos < $c) {
				trigger_error('Overlapping annotations?!');
			}
			// get string from end of last until beginning of next annotation
			$r .= substr($this->stripped, $c, $pos - $c);
			// open annotation
			$r .= '<annotation id="'.$word.'">';
			#$r .= '<'.$word.'>';
			// get word
			$r .= substr($this->stripped, $pos, strlen($word));
			// close annotation
			$r .= '</annotation>';
			#$r .= '</'.$word.'>';
			// advance
			$c = $pos + strlen($word);
		}
		$r .= substr($this->stripped, $c);

		return $r;
	}

}
