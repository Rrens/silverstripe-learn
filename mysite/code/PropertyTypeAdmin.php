<?php

class PropertyTypeAdmin extends ModelAdmin
{
    private static $menu_title = 'Property Type';

    private static $url_segment = 'properties-type';

    private static $managed_models = array(
        'PropertyTypeData'
    );
}
