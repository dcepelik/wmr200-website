<?php

namespace App\Presenters;

use Nette;


class HomepagePresenter extends Nette\Application\UI\Presenter
{
	public function renderDefault()
	{
		$output = shell_exec('wmrq 127.0.0.1 20892');
		$yaml = yaml_parse($output, -1);

		$readings = [];
		foreach ($yaml as $reading) {
			$readings[$reading['sensor']] = $reading;
		}

		$this->template->readings = $readings;
	}
}
