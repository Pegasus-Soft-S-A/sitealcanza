<?php

use Botble\Widget\AbstractWidget;

class TagsWidget extends AbstractWidget
{
    protected $widgetDirectory = 'tags';

    public function __construct()
    {
        parent::__construct([
            'name' => __('Tags'),
            'description' => __('Popular tags'),
            'number_display' => 5,
        ]);
    }
}
