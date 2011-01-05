<?php

class JournalFilterModule extends FilterModule {
  public function onPageHasBeenSet($oPage, $bIsNotFound) {
    if($bIsNotFound || !$oPage->isOfType('journal') || !array_key_exists('entry', $_REQUEST)) {
      return;
    }
    $oEntry = JournalEntryPeer::retrieveByName($_REQUEST['entry']);
    if($oEntry === null) {
      return;
    }
    $oPageString = $oPage->getActivePageString();
    $sTitle = $oPageString->getPageTitle().' – '.$oEntry->getTitle();
    $oPageString->setLinkText($oPageString->getPageTitle());
    $oPageString->setPageTitle($sTitle);
  }
}