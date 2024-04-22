<?php

class PropertyDetailPage extends Page
{
    private static $has_many = array(
        'Properties' => 'PropertyData'
    );
}
