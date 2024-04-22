<?php

class PropertyDetailPage_Controller extends Page_Controller
{
    private static $allowed_actions = array(
        'property',
        'test',
    );

    public function test()
    {
        die('test');
    }
}
