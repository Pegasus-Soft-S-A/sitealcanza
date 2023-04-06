<?php

use Botble\Widget\AbstractWidget;

class CustomMenuWidget extends AbstractWidget
{
    protected $widgetDirectory = 'custom-menu';

    public function __construct()
    {
        parent::__construct([
            'name' => __('Custom Menu'),
            'description' => __('Add a custom menu to your widget area.'),
            'menu_id' => null,
        ]);
    }
}
