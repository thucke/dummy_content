<?php

defined('TYPO3') or die();

(static function () {

    // Page titles:

    $GLOBALS['TCA']['pages']['columns']['title']['config']['fieldWizard'] = array_merge(($GLOBALS['TCA']['pages']['columns']['title']['config']['fieldWizard'] ?? []), \Colorcube\DummyContent\TcaPresets::getFieldControlForHeader());

    $GLOBALS['TCA']['pages']['columns']['subtitle']['config']['fieldWizard'] = array_merge(($GLOBALS['TCA']['pages']['columns']['subtitle']['config']['fieldWizard'] ?? []), \Colorcube\DummyContent\TcaPresets::getFieldControlForHeader());

    $GLOBALS['TCA']['pages']['columns']['nav_title']['config']['fieldWizard'] = array_merge(($GLOBALS['TCA']['pages']['columns']['nav_title']['config']['fieldWizard'] ?? []), \Colorcube\DummyContent\TcaPresets::getFieldControlForHeader());


})();

