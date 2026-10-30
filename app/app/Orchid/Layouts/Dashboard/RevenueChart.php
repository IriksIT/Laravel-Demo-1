<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Dashboard;

use Orchid\Screen\Layouts\Chart;

class RevenueChart extends Chart
{
    protected $target = 'revenueChart';

    protected $title = 'Omzet per maand';

    protected $type = self::TYPE_BAR;

    protected $height = 300;
}
