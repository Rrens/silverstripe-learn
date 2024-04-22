<?php

class FacilityAdmin extends ModelAdmin
{
    private static $menu_title = 'Falities';

    private static $url_segment = 'facilities';

    private static $managed_models = array(
        'FacilityData'
    );
}
