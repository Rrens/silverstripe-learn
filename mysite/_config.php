<?php

global $project;
$project = 'mysite';

global $databaseConfig;
$databaseConfig = array(
    'type' => 'MySQLDatabase',
    'server' => 'localhost',
    'username' => 'root',
    'password' => 'root',
    'database' => 'SS_testing',
    // 'database' => 'SS_mysite',
    'path' => ''
);

// Set the site locale
i18n::set_locale('en_US');

if (Director::isTest()) {
    SS_Log::add_writer(new SS_LogFileWriter('../silverstripe-errors-warnings.log'), SS_Log::WARN, '<=');

    SS_Log::add_writer(new SS_LogFileWriter('../silverstripe-errors.log'), SS_Log::ERR);
}

if (Director::isLive()) {
    SS_Log::add_writer(new SS_LogFileWriter('me@exmaple.com'), SS_Log::ERR);
}
