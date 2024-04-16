<?php

class Page_Controller extends ContentController
{
    /**
     * An array of actions that can be accessed via a request. Each array element should be an action name, and the
     * permissions or conditions required to allow the user to access it.
     *
     * <code>
     * array (
     *     'action', // anyone can access this action
     *     'action' => true, // same as above
     *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
     *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
     * );
     * </code>
     *
     * @var array
     */
    private static $allowed_actions = array();

    public function init()
    {
        parent::init();
        // You can include any CSS or JS required by your project here.
        // See: http://doc.silverstripe.org/framework/en/reference/requirements

        Requirements::css("http://fonts.googleapis.com/css?family=Raleway:300,500,900%7COpen+Sans:400,700,400italic");
        Requirements::css("{$this->ThemeDir()}/static/css/bootstrap.min.css");
        Requirements::css("{$this->ThemeDir()}/static/css/style.css");

        Requirements::javascript("{$this->ThemeDir()}/static/js/common/modernizr.js");
        Requirements::javascript("{$this->ThemeDir()}/static/js/common/jquery-1.11.1.min.js");
        Requirements::javascript("{$this->ThemeDir()}/static/js/common/bootstrap.min.js");
        Requirements::javascript("{$this->ThemeDir()}/static/js/common/bootstrap-datepicker.js");
        Requirements::javascript("{$this->ThemeDir()}/static/js/common/chosen.min.js");
        Requirements::javascript("{$this->ThemeDir()}/static/js/common/bootstrap-checkbox.js");
        Requirements::javascript("{$this->ThemeDir()}/static/js/common/nice-scroll.js");
        Requirements::javascript("{$this->ThemeDir()}/static/js/common/jquery-browser.js");
        Requirements::javascript("{$this->ThemeDir()}/static/js/scripts.js");



        //     <!-- Google Web Font -->
        // <link href="http://fonts.googleapis.com/css?family=Raleway:300,500,900%7COpen+Sans:400,700,400italic"
        //     rel="stylesheet" type="text/css" />

        // <!-- Bootstrap CSS -->
        // <link href="$ThemeDir/static/css/bootstrap.min.css" rel="stylesheet" />

        // <!-- Template CSS -->
        // <link href="$ThemeDir/static/css/style.css" rel="stylesheet" />

        // <!-- Modernizr -->
        // <script src="$ThemeDir/static/js/common/modernizr.js"></script>
    }
}
