<?php
class JournalFileModule extends FileModule {
  private $oJournalPage;
  
  public function __construct($aRequestPath) {
    parent::__construct($aRequestPath);
    Manager::usePath(); //the “journal” bit
    header("Content-Type: application/rss+xml;charset=".Settings::getSetting('encoding', 'db', 'utf-8'));
    $this->oJournalPage = PagePeer::getRootPage()->getPageOfType('journal');
    $sLanguageId = Manager::usePath();
    if(LanguagePeer::languageIsActive($sLanguageId)) {
      Session::getSession()->setLanguage($sLanguageId);
    }
    RichtextUtil::$USE_ABSOLUTE_LINKS = true;
  }
  
  public function renderFile() {
    $oDocument = new DOMDocument();
    $oRoot = $oDocument->createElement("rss");
    $oRoot->setAttribute('version', "2.0");
    $oDocument->appendChild($oRoot);
    $oChannel = $oDocument->createElement("channel");
    self::addSimpleAttribute($oDocument, $oChannel, 'title', $this->oJournalPage->getPageTitle());
    self::addSimpleAttribute($oDocument, $oChannel, 'description', $this->oJournalPage->getPageTitle());
    self::addSimpleAttribute($oDocument, $oChannel, 'link', LinkUtil::absoluteLink(LinkUtil::link('', 'FrontendManager')));
    self::addSimpleAttribute($oDocument, $oChannel, 'language', Session::language());
    self::addSimpleAttribute($oDocument, $oChannel, 'ttl', "15");
    $oRoot->appendChild($oChannel);
    $aJournalEntries = JournalEntryPeer::getMostRecentEntries(10);
    foreach($aJournalEntries as $oJournalEntry) {
      $oItem = $oDocument->createElement('item');
      foreach($oJournalEntry->getRssAttributes($this->oJournalPage) as $sAttributeName => $mAttributeValue) {
        if(is_array($mAttributeValue)) {
          if(ArrayUtil::arrayIsAssociative($mAttributeValue)) {
            //Add one elements with attributes
            $oAttribute = $oDocument->createElement($sAttributeName);
            foreach($mAttributeValue as $sSubAttributeName => $sSubAttributeValue) {
              $oAttribute->setAttribute($sSubAttributeName, $sSubAttributeValue);
            }
            $oChannel->appendChild($oAttribute);
          } else {
            //Add multiple elements with the same name
            foreach($mAttributeValue as $sSubAttributeValue) {
              self::addSimpleAttribute($oDocument, $oItem, $sAttributeName, $sSubAttributeValue);
            }
          }
        } else {
          self::addSimpleAttribute($oDocument, $oItem, $sAttributeName, $mAttributeValue);
        }
      }
      $oChannel->appendChild($oItem);
    }
    print $oDocument->saveXML();
  }
  
  private static function addSimpleAttribute($oDocument, $oChannel, $sAttributeName, $sAttributeValue, $sNamespace = null) {
    if($sNamespace === 'http://www.itunes.com/dtds/podcast-1.0.dtd') {
      $sAttributeName = "itunes:$sAttributeName";
    }
    $oAttribute = $oDocument->createElement($sAttributeName);
    $oAttribute->appendChild($oDocument->createTextNode($sAttributeValue));
    $oChannel->appendChild($oAttribute);
    return $oAttribute;
  }
}