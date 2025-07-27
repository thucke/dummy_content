<?php
defined('TYPO3') || die();


(static function () {
    // TYPO3_MODE is deprecated
    // https://docs.typo3.org/c/typo3/cms-core/main/en-us/Changelog/11.0/Deprecation-92947-DeprecateTYPO3_MODEAndTYPO3_REQUESTTYPEConstants.html
    //if (TYPO3_MODE === 'BE') {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeRegistry'][1582735341] = [
        'nodeName' => 'loremIpsumWizard',
        'priority' => 80,
        'class' => \Colorcube\DummyContent\Form\DummyContentWizard::class
    ];
    //}

})();
