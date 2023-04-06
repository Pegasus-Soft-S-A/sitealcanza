<?php

use Botble\Widget\AbstractWidget;

class BlogCategoriesWidget extends AbstractWidget
{
    protected $widgetDirectory = 'blog-categories';

    public function __construct()
    {
        parent::__construct([
            'name' => 'Blog Categories',
            'description' => __('Widget display blog categories'),
            'number_display' => 10,
        ]);
    }
}
