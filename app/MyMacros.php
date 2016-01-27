<?php

namespace App;

use Latte,
	Latte\MacroNode,
	Latte\PhpWriter;


class MyMacros extends Latte\Macros\MacroSet
{
	public static function install($compiler)
	{
		$me = new static($compiler);
		$me->addMacro('reading', array($me, 'reading'));
		$me->addMacro('status', array($me, 'status'));
	}


	public function reading(MacroNode $node, PhpWriter $writer)
	{
		return $writer->using($node, $this->getCompiler())
			->write('echo isset($readings[%node.word]) ? %escape($readings[%node.word]) : 0');
	}
}
