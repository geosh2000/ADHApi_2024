<?php

if (! function_exists('markdown_to_html')) {
    function markdown_to_html($text, $withBreaks = true)
    {
        require_once APPPATH . 'Libraries/Parsedown/Parsedown.php';
        $Parsedown = new Parsedown();
        $Parsedown->setBreaksEnabled($withBreaks);
        return $Parsedown->text($text);
    }
}