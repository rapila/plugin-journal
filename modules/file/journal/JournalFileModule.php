<?php
class JournalFileModule extends FileModule {
	private $oJournalPage;
	private $aJournalIds;
	
	public function __construct($aRequestPath, Page $oJournalPage = null, $aJournalIds = null) {
		if($aRequestPath === false) {
			$this->oJournalPage = $oJournalPage;
			$this->aJournalIds = $aJournalIds;
		} else {
			parent::__construct($aRequestPath);
			Manager::usePath(); //the “journal” bit
			$this->oJournalPage = PagePeer::getRootPage()->getPageOfType('journal');
			$sLanguageId = Manager::usePath();

			if($sLanguageId && LanguageQuery::languageIsActive($sLanguageId)) {
				Session::getSession()->setLanguage($sLanguageId);
			}
		}
		header("Content-Type: application/rss+xml;charset=".Settings::getSetting('encoding', 'db', 'utf-8'));
		RichtextUtil::$USE_ABSOLUTE_LINKS = LinkUtil::isSSL();
	}
	
	public function renderFile() {
		$oDocument = new DOMDocument();
		$oRoot = $oDocument->createElement("rss");
		$oRoot->setAttribute('version', "2.0");
		$oDocument->appendChild($oRoot);
		$oChannel = $oDocument->createElement("channel");
    /**
    * @todo parametrize the argument
    */
		$oQuery = FrontendJournalEntryQuery::create()->mostRecentFirst()->limit(10);
		if($this->aJournalIds) {
			$oQuery->filterByJournalId($this->aJournalIds);
		}
		self::addSimpleAttribute($oDocument, $oChannel, 'title', $this->oJournalPage->getPageTitle());
		self::addSimpleAttribute($oDocument, $oChannel, 'description', $this->oJournalPage->getDescription());
		self::addSimpleAttribute($oDocument, $oChannel, 'link', LinkUtil::absoluteLink(LinkUtil::link($this->oJournalPage->getFullPathArray(), 'FrontendManager'), null, LinkUtil::isSSL()));
		self::addSimpleAttribute($oDocument, $oChannel, 'language', Session::language());
		self::addSimpleAttribute($oDocument, $oChannel, 'ttl', "15");
		$oRoot->appendChild($oChannel);
		$aJournalEntries = $oQuery->find();
		foreach($aJournalEntries as $oJournalEntry) {
			$oItem = $oDocument->createElement('item');
			foreach($oJournalEntry->getRssAttributes($this->aJournalIds ? $this->oJournalPage : null) as $sAttributeName => $mAttributeValue) {
				self::attributeToNode($oDocument, $oItem, $sAttributeName, $mAttributeValue);
			}
			$oChannel->appendChild($oItem);
		}
		print $oDocument->saveXML();
	}
	
	private static function attributeToNode($oDocument, $oItem, $sAttributeName, $mAttributeValue) {
		if(is_array($mAttributeValue)) {
			if(ArrayUtil::arrayIsAssociative($mAttributeValue)) {
				//Add one element with attributes
				$oAttribute = $oDocument->createElement($sAttributeName);
				foreach($mAttributeValue as $sSubAttributeName => $sSubAttributeValue) {
					if($sSubAttributeName === '__content') {
						$oAttribute->appendChild($oDocument->createTextNode($sSubAttributeValue));
					} else {
						$oAttribute->setAttribute($sSubAttributeName, $sSubAttributeValue);
					}
				}
				$oItem->appendChild($oAttribute);
			} else {
				//Add multiple elements with the same name
				foreach($mAttributeValue as $mSubAttributeValue) {
					self::attributeToNode($oDocument, $oItem, $sAttributeName, $mSubAttributeValue);
				}
			}
		} else {
			self::addSimpleAttribute($oDocument, $oItem, $sAttributeName, $mAttributeValue);
		}
	}
	
	private static function addSimpleAttribute($oDocument, $oChannel, $sAttributeName, $sAttributeValue = '', $sNamespace = null) {
		if($sNamespace === 'http://www.itunes.com/dtds/podcast-1.0.dtd') {
			$sAttributeName = "itunes:$sAttributeName";
		}
		$oAttribute = $oDocument->createElement($sAttributeName);
		$oAttribute->appendChild($oDocument->createTextNode($sAttributeValue));
		$oChannel->appendChild($oAttribute);
		return $oAttribute;
	}
}
