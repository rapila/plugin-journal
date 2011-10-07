<?php

require_once('htmlpurifier/HTMLPurifier.standalone.php');

class JournalPageTypeModule extends PageTypeModule {
	private $sMode;
	private $sContainerName;
	private $iCurrentBackendEntry;
	private $iJournalId;
	private $oEntry;
	
	private static $PAGE_DEFAULT_ACTIONS = array('newest', 'index', 'entry');
	
	public function __construct(Page $oPage) {
		parent::__construct($oPage);
		if(array_key_exists('entry', $_REQUEST)) {
			$this->sMode = 'entry';
			$this->oEntry = JournalEntryPeer::retrieveByName($_REQUEST['entry']);
		} else if(array_key_exists('date', $_REQUEST)) {
			$this->sMode = 'date';
		} else if(array_key_exists('category', $_REQUEST)) {
			$this->sMode = 'category';
		} else if(array_key_exists('index', $_REQUEST)) {
			$this->sMode = 'index';
		} else if(array_key_exists('comment', $_REQUEST)) {
			$this->sMode = 'comment';
		} else { 
			$this->sMode = $this->oPage->getPagePropertyValue('blog_action', 'entry');
		}
		$this->iJournalId = $this->oPage->getPagePropertyValue('journal_id', null);
		$this->sContainerName = $this->oPage->getPagePropertyValue('blog_container', 'content');
		$this->sRecentPostContainerName = $this->oPage->getPagePropertyValue('recent_blogpost_container', null);
		$this->iCurrentBackendEntry = null;
	}
	
	public function setIsDynamicAndAllowedParameterPointers(&$bIsDynamic, &$aAllowedParams, $aModulesToCheck = null) {
		$bIsDynamic = true;
		$aAllowedParams = array('entry', 'date', 'category', 'index', 'newest', 'comment');
	}
	
	public function display(Template $oTemplate, $bIsPreview = false) {
		$sMethod = StringUtil::camelize("display_$this->sMode");
		$this->fillAuxilliaryContainers($oTemplate);
		if(!$oTemplate->hasIdentifier('container', $this->sContainerName)) {
			return;
		}
		return $this->$sMethod($oTemplate);
	}
	
	public static function displayForHome($oPage, $oItemTemplate) {
		$oModule = new JournalPageTypeModule($oPage);
		$aEntries = JournalEntryPeer::getMostRecentEntries(5);
		$oTemplate = new Template(TemplateIdentifier::constructIdentifier('container', $oModule->sContainerName), null, true);
		$oModule->displayJournalEntries($aEntries, $oItemTemplate, $oTemplate);
		return $oTemplate;
	}
	
	private function fillAuxilliaryContainers($oTemplate) {
		if($oTemplate->hasIdentifier('container', $this->sRecentPostContainerName) && $this->sRecentPostContainerName !== null) {
			$aEntries = JournalEntryPeer::getMostRecentEntries(null);
			// $oTemplate->replaceIdentifierMultiple('container', TagWriter::quickTag('h3', null, StringPeer::getString('journal.recent')), $this->sRecentPostContainerName);
			$this->displayJournalEntries($aEntries, $this->constructTemplate('index_entry'), $oTemplate, null, $this->sRecentPostContainerName);
		}
	}
	
	private function displayNewest($oTemplate) {
		$aEntries = JournalEntryPeer::getMostRecentEntries(1);
		$this->displayJournalEntries($aEntries, $this->constructTemplate('short_entry'), $oTemplate);
	}
	
	private function displayIndex($oTemplate) {
		$aEntries = JournalEntryPeer::getMostRecentEntries(null);
		$this->displayJournalEntries($aEntries, $this->constructTemplate('index_entry'), $oTemplate);
	}
	
	private function displayEntry($oTemplate) {
		if($this->oEntry === null) {
			$this->oEntry = JournalEntryPeer::getMostRecentEntry();
		}
		if($this->oEntry === null) {
			LinkUtil::redirect($this->getLink('index'));
		}
		
		// get gallery_images
		$oCriteria = new Criteria();
		$sNameStartsWith = 'ca_'.$this->oEntry->getId();
		$oCriteria->add(DocumentPeer::NAME, "$sNameStartsWith%", Criteria::LIKE);
		$oCriteria->addAscendingOrderByColumn(DocumentPeer::NAME);
		$aImages = DocumentPeer::doSelect($oCriteria);
		$iCountImages = count($aImages);
		if(!isset($_REQUEST['gallery'])) {
			$oEntryTemplatePrototype = $this->constructTemplate('full_entry');
			if($iCountImages) {
				$oEntryTemplatePrototype->replaceIdentifier('gallery_link', LinkUtil::linkToSelf(null, array('gallery' => $this->oEntry->getId())));
				$oEntryTemplatePrototype->replaceIdentifier('image_count', $iCountImages);
			}
			$oEntryTemplatePrototype->replaceIdentifier('captcha', FormFrontendModule::getRecaptchaCode('journal_comment'));
			$this->displayJournalEntries(array($this->oEntry), $oEntryTemplatePrototype, $oTemplate, $this->constructTemplate('full_comment'));
		} else {
			$this->displayJournalImages($aImages, $oTemplate);
		}
	}
	
	//For adding comments
	private function displayComment($oTemplate) {
		$oEntry = JournalEntryPeer::retrieveByPk($_REQUEST['comment']);
		if(!isset($_POST['comment_name']) || $oEntry === null) {
			LinkUtil::redirect($this->getLink('index'));
		}
		$oFlash = Flash::getFlash();
		$oComment = new JournalComment();
		$oComment->setUser($_POST['comment_name']);
		$oFlash->checkForValue('comment_name', 'name');
		$oComment->setEmail($_POST['comment_email']);
		$oFlash->checkForEmail('comment_email', 'email');
		if(!FormFrontendModule::validateRecaptchaInput()) {
			$oFlash->addMessage('captcha');
		}
		$oPurifierConfig = HTMLPurifier_Config::createDefault();
		$oPurifierConfig->set('Cache', 'SerializerPath', MAIN_DIR.'/'.DIRNAME_GENERATED.'/'.DIRNAME_CACHES.'/purifier');
		$oPurifier = new HTMLPurifier($oPurifierConfig);
		$_POST['comment_text'] = $oPurifier->purify($_POST['comment_text']);
		$oComment->setText($_POST['comment_text']);
		$oFlash->checkForValue('comment_text', 'comment');
		$oFlash->finishReporting();
		if(Flash::noErrors()) {
			$oEntry->addJournalComment($oComment);
			$oComment->save();
			LinkUtil::redirect($this->getLink('entry', $oEntry->getName())."#comments");
		}
		$this->displayEntry($oTemplate);
	}
	
	private function displayJournalImages($aImages, $oTemplate) {
		$oListTemplate = new Template('helpers/gallery');
		if($this->oEntry) {
			$oListTemplate->replaceIdentifier('title', $this->oEntry->getTitle());
		}

		foreach($aImages as $oDocument) {
			$oItemTemplate = new Template('helpers/gallery_item');
			$oItemTemplate->replaceIdentifier('url', $oDocument->getDisplayUrl());
			$oItemTemplate->replaceIdentifier('name', $oDocument->getName());
			$oItemTemplate->replaceIdentifier('description', $oDocument->getDescription());
			$oListTemplate->replaceIdentifierMultiple('items', $oItemTemplate);
		}
		$oTemplate->replaceIdentifierMultiple('container', $oListTemplate, 'content');

		return $oTemplate;
	}
	
	private function displayJournalEntries($aEntries, $oEntryTemplatePrototype, $oFullTemplate, $oCommentTemplate = null, $sContainerName = null) { 
		if($sContainerName === null) {
			$sContainerName = $this->sContainerName;
		}
		foreach($aEntries as $oEntry) {
			$oEntryTemplate = clone $oEntryTemplatePrototype;
			$oEntryTemplate->replaceIdentifier('name', $oEntry->getName());
			$oEntryTemplate->replaceIdentifier('id', $oEntry->getId());
			$oEntryTemplate->replaceIdentifier('date', LocaleUtil::localizeDate($oEntry->getCreatedAtTimestamp()));
			$oEntryTemplate->replaceIdentifier('title', $oEntry->getTitle());
			if($oEntryTemplate->hasIdentifier('text')) {
				$oEntryTemplate->replaceIdentifier('text', RichtextUtil::parseStorageForFrontendOutput($oEntry->getText()));
			}
			$oEntryTemplate->replaceIdentifier('link', $this->getLink('entry', $oEntry->getName()));
			if($this->oEntry !== null && $this->oEntry == $oEntry) {
				$oEntryTemplate->replaceIdentifier('current_class', ' class="current"', null, Template::NO_HTML_ESCAPE);
			}
			 
			$oEntryTemplate->replaceIdentifier('comment_action', $this->getLink('comment', $oEntry->getId()));
			$oEntryTemplate->replaceIdentifier('comment_count', $oEntry->countJournalComments());
			if($oEntryTemplate->hasIdentifier('comments') && $oCommentTemplate !== null) {
				$this->displayJournalComments($oEntry, $oEntryTemplate, $oCommentTemplate);
			}
			$oFullTemplate->replaceIdentifierMultiple('container', $oEntryTemplate, $sContainerName);
		}
	}
	
	private function displayJournalComments($oEntry, $oEntryTemplate, $oCommentTemplatePrototype) {
		foreach($oEntry->getJournalComments() as $iCounter => $oComment) {
			$oCommentTemplate = clone $oCommentTemplatePrototype;
			$oCommentTemplate->replaceIdentifier('author', $oComment->getUsername());
			$oCommentTemplate->replaceIdentifier('counter', $iCounter+1);
			$oCommentTemplate->replaceIdentifier('email', $oComment->getEmail());
			$oCommentTemplate->replaceIdentifier('email_hash', md5($oComment->getEmail()));
			$oCommentTemplate->replaceIdentifier('id', $oComment->getId());
			$oCommentTemplate->replaceIdentifier('text', $oComment->getText(), null, Template::NO_HTML_ESCAPE);
			if($oComment->getCreatedAtTimestamp() !== null) {
				$oCommentTemplate->replaceIdentifier('date', LocaleUtil::localizeDate($oComment->getCreatedAtTimestamp()));
			}
			$oEntryTemplate->replaceIdentifierMultiple('comments', $oCommentTemplate, null, Template::LEAVE_IDENTIFIERS);
		}
	}
	
	public static function getJournalLink($oPage, $oEntry) {
		return self::getActionLink($oPage, 'entry', $oEntry->getName());
	}
	
	private static function getActionLink($oBlogPage, $sAction, $sValue) {
		if($oBlogPage->getPagePropertyValue('blog_action', null) !== $sAction) {
			$oCriteria = new Criteria();
			$oCriteria->add(PagePeer::PAGE_TYPE, 'journal');
			$oNullPage = $oBlogPage;
			foreach(PagePeer::doSelect($oCriteria) as $oBlogPage) {
				$sPropertyAction = $oBlogPage->getPagePropertyValue('blog_action', null);
				if($sPropertyAction === $sAction) {
					$oNullPage = null;
					break;
				} else if($sPropertyAction === null) {
					$oNullPage = $oBlogPage;
				}
			}
			if($oNullPage !== null) {
				$oBlogPage = $oNullPage;
			}
		}
		$aLink = $oBlogPage->getFullPathArray();
		array_push($aLink, $sAction);
		if($sValue !== null) {
			array_push($aLink, $sValue);
		}
		return LinkUtil::link($aLink, 'FrontendManager');
	}
	
	private function getLink($sAction, $sValue = null) {
		$oBlogPage = $this->oPage;
		return self::getActionLink($oBlogPage, $sAction, $sValue);
	}
	
	//Admin methods
	public function listJournals() {
		$aJournals = array();
		foreach(JournalQuery::create()->find() as $oJournal) {
			$aJournals[$oJournal->getId()] = $oJournal->getName();
		}
		ErrorHandler::log($this);
		return array('journals' => $aJournals, 'current' => null);
	}
}
