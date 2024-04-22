<?php

class GeneratorGlobalClass
{
    public static function generate($link)
    {
        return str_replace(' ', '-', $link);
    }
}
