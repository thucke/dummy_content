<?php

defined('TYPO3') or die();

(static function () {

    if (isset($GLOBALS['TCA']['tx_news_domain_model_news'])) {

        $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['title']['config']['fieldWizard'] = array_merge(($GLOBALS['TCA']['tx_news_domain_model_news']['columns']['title']['config']['fieldWizard'] ?? []), \Colorcube\DummyContent\TcaPresets::getFieldControlForHeader());
        $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['teaser']['config']['fieldWizard'] = array_merge(($GLOBALS['TCA']['tx_news_domain_model_news']['columns']['teaser']['config']['fieldWizard'] ?? []), \Colorcube\DummyContent\TcaPresets::getFieldControlForTeaser());
        $GLOBALS['TCA']['tx_news_domain_model_news']['columns']['bodytext']['config']['fieldWizard'] = array_merge(($GLOBALS['TCA']['tx_news_domain_model_news']['columns']['bodytext']['config']['fieldWizard'] ?? []), \Colorcube\DummyContent\TcaPresets::getFieldControlForBodytext());
    }

})();

