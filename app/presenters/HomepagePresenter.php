<?php

namespace App\Presenters;

use Nette,
	Latte;


class HomepagePresenter extends Nette\Application\UI\Presenter
{
	public function renderDefault()
	{
		$output = shell_exec('wmrq 127.0.0.1 20892');
		$yaml = @yaml_parse($output, -1) ?: [];

		$readings = [];
		foreach ($yaml as $reading) {
			foreach ($reading as $name => $value) {
				$readings[$reading['sensor'] . "." . $name] = $value;
			}
		}

		$this->template->readings = $readings;

		$this->template->addFilter('batteryIcon', function ($s) {
			if ($s = 'ok') {
				echo '<img src="/img/accept.png" alt="OK" />';
			}
			else {
				echo '<span class=error>Slabá</span>';
			}
		});

		$this->template->addFilter('signalIcon', function ($s) {
			if ($s = 'ok') {
				echo '<img src="/img/accept.png" alt="OK" />';
			}
			else {
				echo '<span class=error>Slabý/mimo dosah</span>';
			}
		});

	}
}
