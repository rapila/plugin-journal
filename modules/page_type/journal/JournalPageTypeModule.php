<?php

require_once('htmlpurifier/HTMLPurifier.standalone.php');

class JournalPageTypeModule extends PageTypeModule {
	private $sCommentMode;
	private $sOverviewMode;
	private $iJournalId = null;
	private $sTemplateSet;
	private $sContainerName;
	private $sRecentPostContainerName;

	/**
	 * @var JournalEntry the entry to be viewed
	 */
	private $oEntry;
	
	private static $PAGE_DEFAULT_ACTIONS = array('newest', 'index', 'entry');
	
	public function __construct(Page $oPage = null, NavigationItem $oNavigationItem = null) {
		parent::__construct($oPage, $oNavigationItem);
		if($oPage) {
			$this->updateFlagsFromProperties();
		}
	}

	public function updateFlagsFromProperties() {
		$this->sOverviewMode = $this->oPage->getPagePropertyValue('blog_overview_action', 'overview');
		$this->sCommentMode = $this->oPage->getPagePropertyValue('blog_comment_mode', 'on');
		$this->iJournalId = $this->oPage->getPagePropertyValue('journal_id', null);
		$this->sTemplateSet = $this->oPage->getPagePropertyValue('blog_template_set', 'default');
		$this->sContainerName = $this->oPage->getPagePropertyValue('blog_container', 'content');
		$this->sRecentPostContainerName = $this->oPage->getPagePropertyValue('recent_blogpost_container', null);
	}
	
	public function setIsDynamicAndAllowedParameterPointers(&$bIsDynamic, &$aAllowedParams, $aModulesToCheck = null) {
		$bIsDynamic = true;
	}
	
	public function display(Template $oTemplate, $bIsPreview = false) {
		$this->fillAuxilliaryContainers($oTemplate);
		if(!$oTemplate->hasIdentifier('container', $this->sContainerName)) {
			return;
		}
		if($bIsPreview) {
			$oTag = TagWriter::quickTag('div', array('id' => 'journal_contents', 'class' => 'filled-container editing'));
			$oTemplate->replaceIdentifier('container', $oTag, $this->sContainerName);
			return;
		}
		$sMethod = 'overview';
		if($this->oNavigationItem instanceof VirtualNavigationItem) {
			$sMethod = substr($this->oNavigationItem->getType(), strlen('journal-'));
			if($this->oNavigationItem->getData() instanceof JournalEntry) {
				$this->oEntry = $this->oNavigationItem->getData();
			}
		}
		$sMethod = StringUtil::camelize("display_$sMethod");
		return $this->$sMethod($oTemplate);
	}
	
	public static function displayForHome($oItemTemplate) {
		$oModule = new JournalPageTypeModule();
		$oTemplate = new Template(TemplateIdentifier::constructIdentifier('container', 'entries'), null, true);
		$oModule->renderJournalEntries(JournalEntryQuery::create()->mostRecent(5), $oItemTemplate, $oTemplate, null, 'entries');
		return $oTemplate;
	}

	private function renderJournalEntries(JournalEntryQuery $oQuery, Template $oEntryTemplatePrototype, Template $oFullTemplate, Template $oCommentTemplate = null, $sContainerName = null) {
		if($sContainerName === null) {
			$sContainerName = $this->sContainerName;
		}
		if($this->iJournalId) {
			$oQuery->filterByJournalId($this->iJournalId);
		}
		foreach($oQuery->orderByCreatedAt(Criteria::DESC)->excludeDraft()->find() as $oEntry) {
			$oFullTemplate->replaceIdentifierMultiple('container', $this->renderEntry($oEntry, clone $oEntryTemplatePrototype), $sContainerName);
		}
	}

	private function renderEntry(JournalEntry $oEntry, Template $oEntryTemplate) {
		$oEntryTemplate->replaceIdentifier('slug', $oEntry->getSlug());
		$oEntryTemplate->replaceIdentifier('name', $oEntry->getSlug());
		$oEntryTemplate->replaceIdentifier('id', $oEntry->getId());
		$oEntryTemplate->replaceIdentifier('date', LocaleUtil::localizeDate($oEntry->getCreatedAtTimestamp()));
		$oEntryTemplate->replaceIdentifier('title', $oEntry->getTitle());
		$oEntryTemplate->replaceIdentifier('comment_count', $oEntry->countJournalComments());
		$oEntryTemplate->replaceIdentifier('link', LinkUtil::link($oEntry->getLink($this->oPage), 'FrontendManager'));
		
		if($oEntryTemplate->hasIdentifier('text')) {
			$oEntryTemplate->replaceIdentifier('text', RichtextUtil::parseStorageForFrontendOutput($oEntry->getText()));
		}
		
		if($this->oEntry !== null && $this->oEntry == $oEntry) {
			$oEntryTemplate->replaceIdentifier('current_class', ' class="current"', null, Template::NO_HTML_ESCAPE);
		}

		if($oEntryTemplate->hasIdentifier('journal_comments')) {
			$oEntryTemplate->replaceIdentifier('journal_comments', $this->renderComments($oEntry));
		}
		if($oEntryTemplate->hasIdentifier('journal_gallery') && $oEntry->countJournalEntryImages() > 0) {
			$oEntryTemplate->replaceIdentifier('journal_gallery', $this->renderGallery($oEntry));
		}
		
		return $oEntryTemplate;
	}

	private function renderGallery(JournalEntry $oEntry) {
		$oEntryTemplate = $this->constructTemplate('journal_gallery');

		$oListTemplate = new Template('helpers/gallery');
		$oListTemplate->replaceIdentifier('title', $this->oEntry->getTitle());

		foreach($this->oEntry->getJournalEntryImages() as $oJournalEntryImage) {
			$oDocument = $oJournalEntryImage->getDocument();
			$oItemTemplate = new Template('helpers/gallery_item');
			$oDocument->renderListItem($oItemTemplate);
			$oListTemplate->replaceIdentifierMultiple('items', $oItemTemplate);
		}
		
		$oEntryTemplate->replaceIdentifier('gallery', $oListTemplate);
		return $oEntryTemplate;
	}


	private function renderComments(JournalEntry $oEntry) {
		$oEntryTemplate = $this->constructTemplate('comments');
		$oEntryTemplate->replaceIdentifier('comment_count', $oEntry->countJournalComments());
		$oCommentTemplatePrototype = $this->constructTemplate('full_comment');
		foreach($oEntry->getJournalComments() as $iCounter => $oComment) {
			$oEntryTemplate->replaceIdentifierMultiple('comments', $this->renderComment($oComment, clone $oCommentTemplatePrototype, $iCounter), null, Template::LEAVE_IDENTIFIERS);
		}
		if($oEntryTemplate->hasIdentifier('leave_comment')) {
			$oLeaveCommentTemplate = $this->constructTemplate('leave_comment');
			switch($this->sCommentMode) {
				case "moderated":
					$oLeaveCommentTemplate = $this->constructTemplate('leave_comment_moderated');
				case "on":
					$oLeaveCommentTemplate->replaceIdentifier('captcha', FormFrontendModule::getRecaptchaCode('journal_comment'));
					$oLeaveCommentTemplate->replaceIdentifier('comment_action', $oEntry->getLink($this->oPage));
					break;
				default:
					$oLeaveCommentTemplate = null;
			}
			$oEntryTemplate->replaceIdentifier('leave_comment', $oLeaveCommentTemplate);
		}
		return $oEntryTemplate;
	}

	private function renderComment(JournalComment $oComment, Template $oCommentTemplate, $iCounter) {
		$oCommentTemplate->replaceIdentifier('author', $oComment->getUsername());
		$oCommentTemplate->replaceIdentifier('counter', $iCounter+1);
		$oCommentTemplate->replaceIdentifier('email', $oComment->getEmail());
		$oCommentTemplate->replaceIdentifier('email_hash', md5($oComment->getEmail()));
		$oCommentTemplate->replaceIdentifier('id', $oComment->getId());
		$oCommentTemplate->replaceIdentifier('text', $oComment->getText(), null, Template::NO_HTML_ESCAPE);
		if($oComment->getCreatedAtTimestamp() !== null) {
			$oCommentTemplate->replaceIdentifier('date', LocaleUtil::localizeDate($oComment->getCreatedAtTimestamp()));
		}
		return $oCommentTemplate;
	}


	private function fillAuxilliaryContainers(Template $oTemplate) {
		if($oTemplate->hasIdentifier('container', $this->sRecentPostContainerName) && $this->sRecentPostContainerName !== null) {
			$this->renderJournalEntries(JournalEntryQuery::create()->mostRecent(null), $this->constructTemplate('list_entry'), $oTemplate, null, $this->sRecentPostContainerName);
		}
	}
	
	private function displayList($oTemplate) {
		$this->renderJournalEntries(JournalEntryQuery::create()->mostRecent(20), $this->constructTemplate('list_entry'), $oTemplate);
	}
	
	private function displayOverview($oTemplate) {
		$this->renderJournalEntries(JournalEntryQuery::create(), $this->constructTemplate('short_entry'), $oTemplate);
	}
	
	private function displayEntry($oTemplate) {
		if($this->oEntry === null) {
			LinkUtil::redirect($this->getLink('index'));
		}
		$oTemplate->replaceIdentifier('container', $this->renderEntry($this->oEntry, $this->constructTemplate('full_entry')), $this->sContainerName);
	}

	//For adding comments
	private function displayComment($oTemplate) {
		$oEntry = JournalEntryPeer::retrieveByPK($_REQUEST['comment']);
		if(!isset($_POST['comment_name']) || $oEntry === null) {
			LinkUtil::redirect($this->getLink('index'));
		}
		$oFlash = Flash::getFlash();
		$oComment = new JournalComment();
		$oComment->setUsername($_POST['comment_name']);
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
	
	//Override from parent
	protected function constructTemplate($sTemplateName = null, $bForceGlobalTemplatesDir = false) {
		if($this->sTemplateSet) {
			try {
				return parent::constructTemplate($sTemplateName, array(DIRNAME_MODULES, self::getType(), $this->getModuleName(), 'templates', $this->sTemplateSet));
			} catch (Exception $e) {}
		}
		return parent::constructTemplate($sTemplateName, array(DIRNAME_MODULES, self::getType(), $this->getModuleName(), 'templates', 'default'));
	}









	
	/*
		***** Admin Methods *****
	*/
	public function detailWidget() {
		$oWidget = WidgetModule::getWidget('journal_entry_detail', null, $this->oPage);
		return $oWidget->getSessionKey();
	}
	
	public function currentMode() {
		return $this->sOverviewMode;
	}

	public function currentCommentMode() {
		return $this->sCommentMode;
	}

	public function currentJournal() {
		return $this->iJournalId;
	}
	
	public function listJournals() {
		$aJournals = array();
		foreach(JournalQuery::create()->find() as $oJournal) {
			$aJournals[$oJournal->getId()] = $oJournal->getName();
		}
		return array('options' => $aJournals, 'current' => $this->iJournalId);
	}

	public function listTemplateSets() {
		$aResult = array();
		foreach(ResourceFinder::create(array(DIRNAME_MODULES, self::getType(), $this->getModuleName(), DIRNAME_TEMPLATES))->addDirPath()->returnObjects()->find() as $oSet) {
			$aResult[] = $oSet->getFileName();
		}
		return array('options' => $aResult, 'current' => $this->sTemplateSet);
	}

	public function listContainers() {
		$aContainers = $this->oPage->getTemplate()->identifiersMatching("container", Template::$ANY_VALUE);
		$aResult = array();
		foreach($aContainers as $oContainer) {
			$aResult[] = $oContainer->getValue();
		}
		return array('options' => $aResult, 'current' => $this->sContainerName);
	}

	public function journalProperties() {
		if($this->iJournalId === CriteriaListWidgetDelegate::SELECT_WITHOUT) {
			return null;
		}
		$oJounal = JournalPeer::retrieveByPK($this->iJournalId);
		if($oJounal) {
			return $oJounal->toArray();
		}
		// should never happen...
		return null;
	}

	private $oJournalEntryList = null;
	public function entryList() {
		$this->oJournalEntryList = new JournalEntryListWidgetModule();
		$this->oJournalEntryList->getDelegate()->setJournalId($this->iJournalId);
		
		$oIncluder = new ResourceIncluder();
		JournalEntryListWidgetModule::includeResources($oIncluder);

		return $oIncluder->getIncludes()->render().$this->oJournalEntryList->doWidget()->render();
		$oListWidget = $this->oJournalEntryList->getList();
		return array($oListWidget->getModuleName(), $oListWidget->getSessionKey());
	}

	public function setCurrentJournal($iJournalId) {
		$this->iJournalId = $iJournalId === null ? CriteriaListWidgetDelegate::SELECT_WITHOUT : $iJournalId;
		if($this->oJournalEntryList) {
			$this->oJournalEntryList->getDelegate()->setJournalId($this->iJournalId);
		}
	}
	
	private function validate($aData) {
		$oFlash = Flash::getFlash();
		$oFlash->setArrayToCheck($aData);
		$oFlash->checkForValue('journal_name', 'journal.name_required');
		$oFlash->finishReporting();
	}

	public function saveJournal($aData) {
		$oJournal = JournalPeer::retrieveByPK($this->iJournalId);
		if($oJournal === null) {
			$oJournal = new Journal();
		}
		$this->validate($aData);
		if(!Flash::noErrors()) {
			throw new ValidationException();
		}
		$oJournal->setName($aData['journal_name']);
		$oJournal->setDescription($aData['journal_description']);
		$oJournal->save();
		$this->iJournalId = $oJournal->getId();
		$this->oPage->updatePageProperty('blog_overview_action', $aData['mode']);
		$this->oPage->updatePageProperty('journal_id', $this->iJournalId);
		$this->oPage->updatePageProperty('blog_template_set', $aData['template_set']);
		$this->oPage->updatePageProperty('blog_container', $aData['container']);
		$this->oPage->updatePageProperty('blog_comment_mode', $aData['comment_mode']);
		$this->updateFlagsFromProperties();
	}
}
