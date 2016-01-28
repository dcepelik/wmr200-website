<?php

namespace App\Presenters;

use Nette;
use Nette\Utils\Strings;
use Nette\Utils\Image;


class GraphPresenter extends Nette\Application\UI\Presenter
{
	public function renderRender($sensor, $delay)
	{
		$file = "/tmp/wmr-$sensor.png";

		$graphOptions = [
			'humidity' => [
				'--vertical-label', 'procenta',
				'--title', 'Vlhkost',
				'DEF:humidity=/var/wmrd/rrd/temp1.rrd:humidity:AVERAGE',
				'LINE1:humidity#000000',
			],

			'wind' => [
				'--vertical-label', 'm/s',
				'--title', 'Vítr',
				'DEF:gust_speed=/var/wmrd/rrd/wind.rrd:gust_speed:MAX',
				'DEF:avg_speed=/var/wmrd/rrd/wind.rrd:avg_speed:AVERAGE',
				'AREA:gust_speed#FF0000:rychlost poryvů',
				'LINE1:avg_speed#000000:průměrná rychlost',
			],

			'pressure' => [
				'--vertical-label', 'hPa',
				'--title', 'Atmosférický tlak',
				'DEF:pressure=/var/wmrd/rrd/baro.rrd:pressure:AVERAGE',
				'LINE1:pressure#000000',
			],

			'rain' => [
				'--vertical-label', 'mm/h',
				'--title', 'Srážky',
				'DEF:rate=/var/wmrd/rrd/rain.rrd:rate:AVERAGE',
				'LINE1:rate#000000:průměrné srážky',
			],
		];

		for ($i = 0; $i < 10; $i++) {
			$graphOptions["temp$i"] = [
				'--title', 'Teplota',
				'--vertical-label', '°C',
				"DEF:temp_avg=/var/wmrd/rrd/temp$i.rrd:temp:AVERAGE",
				"DEF:temp_max=/var/wmrd/rrd/temp$i.rrd:temp:MAX",
				"DEF:temp_min=/var/wmrd/rrd/temp$i.rrd:temp:MIN",
				"DEF:dewpoint=/var/wmrd/rrd/temp$i.rrd:dewpoint:AVERAGE",
				'LINE1:temp_max#FF0000:maximální teplota',
				'LINE1:temp_min#0000FF:minimální teplota',
				'LINE1:temp_avg#000000:průměrná teplota',
				'LINE1:dewpoint#00FF00:rosný bod',
				'GPRINT:temp_min:MIN:"Minimální\:%8.2lf %s\n"',
				'GPRINT:temp_avg:AVERAGE:"Průměrná\:%8.2lf %s"',
				'GPRINT:temp_max:MAX:"Maximální\:%8.2lf %s\n"',
			];

		}

		$options = array_merge([
			'--start', "N-$delay",
			'--slope-mode',
			'--width', 500
		], $graphOptions[$sensor]);

		rrd_graph($file, $options);

		$img = Image::fromFile($file);
		$img->send();
	}
}
