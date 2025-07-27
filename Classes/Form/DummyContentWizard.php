<?php
declare(strict_types=1);

namespace Colorcube\DummyContent\Form;

use TYPO3\CMS\Backend\Form\AbstractNode;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Routing\SiteMatcher;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Page\JavaScriptModuleInstruction;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Domain\ConsumableString;

/**
 * Dummy Content dummy text wizard
 */
class DummyContentWizard extends AbstractNode
{
    /**
     * Render buttons which insert dummy text in form form fields
     *
     * @return array
     */
    public function render(): array
    {
        $languageService = $this->getLanguageService();

        $options = $this->data['renderData']['fieldWizardOptions'];
        $actions = $options['actions'];

        $parameterArray = $this->data['parameterArray'];
        $itemName = $parameterArray['itemFormElName'];
        $isRTE = (bool)($parameterArray['fieldConf']['config']['enableRichtext'] ?? false);

        $dcwEventListenerJS = "";
        $html = [];
        $html[] = '<div class="help-block">';
        $html[] = htmlspecialchars($languageService->sL('LLL:EXT:dummy_content/Resources/Private/Language/Labels.xlf:dummyText')) . ' ';
        foreach ($actions as $action) {
            $title = $languageService->sL($action['title']);

            unset($action['title']);
            $action['html'] = $isRTE;
            $lang = $this->getRecordLanguage($this->data);
            // is this the right way to get an instance with requirejs?
            $loremIpsum = "var loremIpsum = LoremIpsum(" . json_encode($action) . ", " . json_encode($lang) . "); ";
            $dcwId = uniqid('dcw_');
            $actionDcwEventListener = "";

            if ($isRTE) {
                $itemName = $this->sanitizeFieldId($itemName);
                $eventListenerHandlerName = "eventListenerRteHandler";
            } else {
                $eventListenerHandlerName = "eventListenerHandler";
            }
            $actionDcwEventListener = "document.getElementById('" . $dcwId . "').addEventListener('click', function() {";
            $actionDcwEventListener .= "var itemname = this.attributes['data-target'].value;";
            $actionDcwEventListener .= $eventListenerHandlerName . "(itemname, '" . json_encode($action) . "', " . json_encode($lang) . ");});";

            // every button gets its own unique id (dcw -> "Dummy Content Wizard")
            $html[] = '<button id="' . $dcwId . '" type="button" class="btn btn-info" data-target="' . $itemName . '">' . htmlspecialchars($title) . '</button>';

            $dcwEventListenerJS .= $actionDcwEventListener;
        }
        $html[] = '</div>';

        $result['html'] = implode(LF, $html);

        $result['javaScriptModules'][] =
            JavaScriptModuleInstruction::create('@colorcube/dummy_content/LoremIpsum.js');

        \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\AssetCollector::class)
            ->addInlineJavaScript ('dcwEventListenerJS_' . uniqid('dcwjs_'), $dcwEventListenerJS, [ 'nonce' => $this->getNonceAttribute(), 'data-dcw' => $dcwId ]);

        return $result;
    }


    /**
     * Returns a the request object of the current context
     *
     * @return ServerRequestInterface
     */
    private function getRequest(): ServerRequestInterface
    {
        return $GLOBALS['TYPO3_REQUEST'];
    }

    /**
     * Returns a new nonce attribute value
     *
     * @return ConsumableString
     */
    protected function getNonceAttribute()
    {
        $request = $this->getRequest();
        $nonce = "";
        $nonceAttribute = $request->getAttribute('nonce');
        if ($nonceAttribute instanceof ConsumableString) {
            return $nonceAttribute;
        } else {
            return $nonceAttribute;
        }
    }

    /**
     * Returns language iso 2 code for record
     *
     * not perfectly working!
     *
     * @param $data
     * @return string
     */
    protected function getRecordLanguage($data)
    {
        try {
            $site = GeneralUtility::makeInstance(SiteMatcher::class)->matchByPageId((int)$data['databaseRow']['pid']);
            $siteLanguage = $site->getLanguageById((int)($data['databaseRow']['sys_language_uid'][0] ?? 0));
            return $siteLanguage->getLocale()->getLanguageCode() ?? 'en';
        } catch (\Exception $e) {
        }
        return 'en';
    }


    /**
     * @param string $itemFormElementName
     * @return string
     */
    protected function sanitizeFieldId(string $itemFormElementName): string
    {
        $fieldId = preg_replace('/[^a-zA-Z0-9_:.-]/', '_', $itemFormElementName);
        return htmlspecialchars(preg_replace('/^[^a-zA-Z]/', 'x', $fieldId));
    }

    /**
     * @return LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }

    /**
     * @return BackendUserAuthentication
     */
    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }
}

