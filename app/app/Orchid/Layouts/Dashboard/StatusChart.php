<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Dashboard;

use Orchid\Screen\Layouts\Chart;

class StatusChart extends Chart
{
    protected $target = 'statusChart';

    protected $title = 'Bestellingen per status';

    protected $type = self::TYPE_PIE;

    protected $height = 300;
}
