<?php

class PropertySearchPage extends Page
{
    private static $has_many = array(
        'Property' => 'PropertyData'
    );
}
