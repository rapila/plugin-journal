<?php

require_once('htmlpurifier/HTMLPurifier.standalone.php');

class JournalPageTypeModule extends PageTypeModule {
	private $sCommentMode;
	private $sOverviewMode;
	private $iJournalId = null;
	private $sTag = null;
	private $iPage = null;
	private $sTemplateSet;
	private $sContainerName;
	private $sAuxiliaryContainer;
	private $bDatesHidden;
	private $aWidgets;
	
	const ALLOWED_POINTER_PAGE = 'page';
	const ALLOWED_POINTER_TAG = 'tag';

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
		if(isset($_REQUEST[self::ALLOWED_POINTER_TAG])) {
			$this->sTag = $_REQUEST[self::ALLOWED_POINTER_TAG];
		}
	}

	public function updateFlagsFromProperties() {
		$this->sOverviewMode = $this->oPage->getPagePropertyValue('blog_overview_action', 'overview');
		$this->sCommentMode = $this->oPage->getPagePropertyValue('blog_comment_mode', 'on');
		$this->iJournalId = $this->oPage->getPagePropertyValue('journal_id', null);
		$this->sTemplateSet = $this->oPage->getPagePropertyValue('blog_template_set', 'default');
		$this->sContainerName = $this->oPage->getPagePropertyValue('blog_container', 'content');
		$this->sAuxiliaryContainer = $this->oPage->getPagePropertyValue('blog_auxiliary_container', null);
		$this->bDatesHidden = !!$this->oPage->getPagePropertyValue('blog_dates_hidden', null);
		$this->aWidgets = $this->oPage->getPagePropertyValue('blog_widgets', '');
		if($this->aWidgets === '') {
			$this->aWidgets = array();
		} else {
			$this->aWidgets = explode(',', $this->aWidgets);
		}
	}
	
	public function setIsDynamicAndAllowedParameterPointers(&$bIsDynamic, &$aAllowedParams, $aModulesToCheck = null) {
		$bIsDynamic = true;
		$aAllowedParams = array('tag', 'page');
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
		if($this->sTag) {
			$oQuery->filterByTagName($this->sTag);
		}
		foreach($oQuery->orderByCreatedAt(Criteria::DESC)->excludeDraft()->find() as $oEntry) {
			$oFullTemplate->replaceIdentifierMultiple('container', $this->renderEntry($oEntry, clone $oEntryTemplatePrototype), $sContainerName);
		}
	}

	private function renderEntry(JournalEntry $oEntry, Template $oEntryTemplate) {
		$oCommentQuery = JournalCommentQuery::create()->excludeUnverified();
		$oEntryTemplate->replaceIdentifier('journal_title', $oEntry->getJournal()->getName());
		$oEntryTemplate->replaceIdentifier('slug', $oEntry->getSlug());
		$oEntryTemplate->replaceIdentifier('name', $oEntry->getSlug());
		$oEntryTemplate->replaceIdentifier('user_name', $oEntry->getUserRelatedByCreatedBy()->getFullName());
		$oEntryTemplate->replaceIdentifier('id', $oEntry->getId());
		$oEntryTemplate->replaceIdentifier('date', LocaleUtil::localizeDate($oEntry->getCreatedAtTimestamp()));
		$oEntryTemplate->replaceIdentifier('title', $oEntry->getTitle());
		$iCountComments = $oEntry->countJournalComments($oCommentQuery);
		$oEntryTemplate->replaceIdentifier('comment_count', $iCountComments > 0 ? $iCountComments : StringPeer::getString('journal.comment_count.none'));
		$oEntryTemplate->replaceIdentifier('link', LinkUtil::link($oEntry->getLink($this->oPage), 'FrontendManager'));
		
		if($oEntryTemplate->hasIdentifier('text')) {
			$oEntryTemplate->replaceIdentifier('text', RichtextUtil::parseStorageForFrontendOutput($oEntry->getText()));
		}
		
		if($this->oEntry !== null && $this->oEntry == $oEntry) {
			$oEntryTemplate->replaceIdentifier('current_class', ' class="current"', null, Template::NO_HTML_ESCAPE);
		}

		if($oEntryTemplate->hasIdentifier('journal_comments')) {
			$oEntryTemplate->replaceIdentifier('journal_comments', $this->renderComments($oEntry->getJournalComments($oCommentQuery), $oEntry));
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


	private function renderComments($aComments, JournalEntry $oEntry = null) {
		$oEntryTemplate = $this->constructTemplate('comments');
		$oEntryTemplate->replaceIdentifier('comment_count', count($aComments));
		$oCommentTemplatePrototype = $this->constructTemplate('full_comment');
		foreach($aComments as $iCounter => $oComment) {
			$oEntryTemplate->replaceIdentifierMultiple('comments', $this->renderComment($oComment, clone $oCommentTemplatePrototype, $iCounter), null, Template::LEAVE_IDENTIFIERS);
		}
		if($oEntryTemplate->hasIdentifier('leave_comment')) {
			$oEntryTemplate->replaceIdentifier('leave_comment', $this->renderAddComment($oEntry));
		}
		return $oEntryTemplate;
	}

	private function renderAddComment(JournalEntry $oEntry = null) {
		if($oEntry === null) {
			$oEntry = $this->oEntry;
		}
		if($this->sCommentMode === 'off') {
			return null;
		}
		$oLeaveCommentTemplate = $this->constructTemplate('leave_comment');
		switch($this->sCommentMode) {
			case "moderated":
				$oLeaveCommentTemplate = $this->constructTemplate('leave_comment_moderated');
			case "on":
				$oLeaveCommentTemplate->replaceIdentifier('captcha', FormFrontendModule::getRecaptchaCode('journal_comment'));
				$oLeaveCommentTemplate->replaceIdentifier('comment_action', LinkUtil::link($oEntry->getLink($this->oPage, 'add_comment')));
				break;
			default:
				$oLeaveCommentTemplate = null;
		}
		return $oLeaveCommentTemplate;
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
		if($this->sAuxiliaryContainer && $oTemplate->hasIdentifier('container', $this->sAuxiliaryContainer)) {
			foreach($this->aWidgets as $sWidget) {
				$sMethodName = "render".StringUtil::camelize($sWidget, true)."Widget";
				$oTemplate->replaceIdentifierMultiple('container', $this->$sMethodName(), $this->sAuxiliaryContainer);
			}
		}
	}
	
	private function renderRecentEntriesWidget() {
		$oTemplate = new Template(TemplateIdentifier::constructIdentifier('container', 'entries'), null, true);
		$this->renderJournalEntries(JournalEntryQuery::create()->mostRecent(), $this->constructTemplate('list_entry'), $oTemplate, null, 'entries');
		return $oTemplate;
	}
	
	private function renderCalendarWidget() {
		// prepare
		$oQuery=JournalEntryQuery::create()->distinct()->filterByJournalId($this->iJournalId)->filterByIsPublished(true)->clearSelectColumns();
		$oQuery->withColumn('DAY('.JournalEntryPeer::CREATED_AT.')', 'Day');
		$oQuery->withColumn('MONTH('.JournalEntryPeer::CREATED_AT.')', 'Month');
		$oQuery->withColumn('YEAR('.JournalEntryPeer::CREATED_AT.')', 'Year');
		$aResult = $oQuery->orderByYearMonthDay()->select('Year', 'Month', 'Day')->find();
		
		$oTemplate = $this->constructTemplate('calendar');
		$oMonthPrototype = $this->constructTemplate('calendar_item_month');
		$oYearPrototype = $this->constructTemplate('calendar_item_year');
		$oDayPrototype = $this->constructTemplate('calendar_item');
		$sYearDummy = null;
		$sMonthDummy = null;
		$bYearHasChanged = false;

		foreach($aResult as $aDate) {
			// make year template whenever the year changes and add it to main template
		// 	if($aDate['Year'] !== $sYearDummy) {
		// 		$bYearHasChanged = true;
		// 		$sYearDummy = $aDate['Year'];
		// 		$oYearTemplate = clone $oYearPrototype;
		// 		$oYearTemplate->replaceIdentifier('year', $aDate['Year']);
		// 		$oYearTemplate->replaceIdentifier('link', $this->oPage->getLinkArray($aDate['Year']));
		// 		$oTemplate->replaceIdentifierMultiple('calendar_item', $oYearTemplate);	
		// 	} else {
		// 		$bYearHasChanged = false;
		// 	}
		// 	// make month template whenever month changes (or year, because it can happen that two months are the same when a year changes) 
		// 	// and add it to year template
		// 	if($bYearHasChanged || $aDate['Month'] !== $sMonthDummy) {
		// 		$sMonthDummy = $aDate['Month'];
		// 		$oMonthTemplate = clone $oMonthPrototype;
		// 		$oMonthTemplate->replaceIdentifier('year', $aDate['Year']);
		// 		$oMonthTemplate->replaceIdentifier('month', $aDate['Month']);
		// 		$oMonthTemplate->replaceIdentifier('link', $this->oPage->getLinkArray($aDate['Year'], $aDate['Month']));
		// 		$oYearTemplate->replaceIdentifierMultiple('month_item', $oMonthTemplate);	
		// 	} 			
		// 	// make day item template and add it to month template
		// 	$oDayTemplate = clone $oDayPrototype;
		// 	$oDayTemplate->replaceIdentifier('year', $aDate['Year']);
		// 	$oDayTemplate->replaceIdentifier('month', $aDate['Month']);
		// 	$oDayTemplate->replaceIdentifier('day', $aDate['Day']);
		// 	$oDayTemplate->replaceIdentifier('link', LinkUtil::link($this->oPage->getLinkArray($aDate['Year'], $aDate['Month'], $aDate['Day'])));
		// 	$oMonthTemplate->replaceIdentifierMultiple('day_item', $oDayTemplate);
		// }

		foreach($aResult as $aDate) {
			$oItemTemplate = clone $oDayPrototype;
			$oItemTemplate->replaceIdentifier('year', $aDate['Year']);
			$oItemTemplate->replaceIdentifier('month', $aDate['Month']);
			$oItemTemplate->replaceIdentifier('day', $aDate['Day']);
			$oItemTemplate->replaceIdentifier('link', LinkUtil::link($this->oPage->getLinkArray($aDate['Year'], $aDate['Month'], $aDate['Day'])));
			$oTemplate->replaceIdentifierMultiple('calendar_item', $oItemTemplate);
		}
		return $oTemplate;
	}
	
	private function renderTagCloudWidget() {
		$aTags = TagQuery::create()->orderByName()->withTagInstanceCountFilteredByModel('JournalEntry')->find()->toKeyValue('Name', 'TagInstanceCount');
		
		// Calculation of font-size
		$iMin = min($aTags);
		$iMax = max($aTags);
		$iDiff = $iMax - $iMin;
		$iMinPixelFontSize = 10;
		$iMaxPixelFontSize = 20;
		if(array_sum($aTags) > 40) {
			$iMaxPixelFontSize = 30;
		}
		$iPixelStep = ($iMaxPixelFontSize - $iMinPixelFontSize) / $iDiff;
		$oTemplate = $this->constructTemplate('tag');
		$oItemPrototype = $this->constructTemplate('tag_item');
		$sLabelEntry = StringPeer::getString('wns.');
		foreach($aTags as $sName => $iCount) {
			$oItemTemplate = clone $oItemPrototype;
			$iFontSize = (int) ceil($iMinPixelFontSize + (($iCount - $iMin) * $iPixelStep));
			$oItemTemplate->replaceIdentifier('font_size', $iFontSize);
			$oItemTemplate->replaceIdentifier('line_height', ceil($iFontSize * 1.2));
			$oItemTemplate->replaceIdentifier('count_title', StringPeer::getString($iCount === 1 ? 'tag.label_entry' : 'tag.label_entries'));
			if(($this->sTag == $sName)) {
				$oItemTemplate->replaceIdentifier('class_active', ' active');
			}
			$oItemTemplate->replaceIdentifier('tag_link', LinkUtil::link(array_merge($this->oPage->getLinkArray(self::ALLOWED_POINTER_TAG, $sName))));
			$oItemTemplate->replaceIdentifier('tag_name', ucfirst(StringPeer::getString('tag.'.$sName, null, $sName)));
			$oTemplate->replaceIdentifierMultiple('tag_item', $oItemTemplate);
		}
		return $oTemplate;
	}
	
	private function displayList($oTemplate) {
		$this->renderJournalEntries(JournalEntryQuery::create()->mostRecent(20), $this->constructTemplate('list_entry'), $oTemplate);
	}
	
	private function displayOverview($oTemplate) {
		$this->renderJournalEntries(JournalEntryQuery::create(), $this->constructTemplate('short_entry'), $oTemplate);
	}
	
	private function displayEntry($oTemplate) {
		if($this->oEntry === null) {
			LinkUtil::redirect(LinkUtil::link($this->oPage->getLinkArray(), 'FrontendManager'));
		}
		$oTemplate->replaceIdentifier('container', $this->renderEntry($this->oEntry, $this->constructTemplate('full_entry')), $this->sContainerName);
	}

	//For adding comments
	private function displayAddComment($oTemplate) {
		if($this->oEntry === null) {
			return $this->displayEntry($oTemplate);
		}
		if($this->sCommentMode === 'off') {
			LinkUtil::redirect(LinkUtil::link($oEntry->getLink()));
		}
		if(Manager::isPost()) {
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
			$oPurifierConfig->set('Cache.SerializerPath', MAIN_DIR.'/'.DIRNAME_GENERATED.'/'.DIRNAME_CACHES.'/purifier');
			$oPurifierConfig->set('HTML.Doctype', 'XHTML 1.0 Transitional');
			$oPurifierConfig->set('AutoFormat.AutoParagraph', true);
			$oPurifier = new HTMLPurifier($oPurifierConfig);
			$_POST['comment_text'] = $oPurifier->purify($_POST['comment_text']);
			$oComment->setText($_POST['comment_text']);
			$oFlash->checkForValue('comment_text', 'comment');
			$oFlash->finishReporting();
			if(isset($_POST['preview'])) {
				$oComment->setCreatedAt(date('c'));
				$oTemplate->replaceIdentifier('container', $this->renderComments(array($oComment), $this->oEntry), $this->sContainerName);
				return;
			}
			if(Flash::noErrors()) {
				$this->oEntry->addJournalComment($oComment);
				if($this->sCommentMode === 'moderated') {
					$oComment->setIsPublished(false);
				}
				$oComment->save();
				switch($this->sCommentMode) {
					case "moderated":
					case "notified":
						$oEmailContent = $this->constructTemplate('e_mail_comment_'.$this->sCommentMode);
						$oEmailContent->replaceIdentifier('email', $oComment->getEmail());
						$oEmailContent->replaceIdentifier('user', $oComment->getUsername());
						$oEmailContent->replaceIdentifier('comment', $oComment->getText());
						$oEmailContent->replaceIdentifier('entry', $this->oEntry->getTitle());
						$oEmailContent->replaceIdentifier('journal', $this->oEntry->getJournal()->getName());
						$oEmailContent->replaceIdentifier('entry_link', LinkUtil::absoluteLink(LinkUtil::link($this->oEntry->getLink())));
						$oEmailContent->replaceIdentifier('deactivation_link', LinkUtil::absoluteLink(LinkUtil::link(array('journal_comment_moderation', $oComment->getActivationHash(), 'deactivate'), 'FileManager')));
						$oEmailContent->replaceIdentifier('activation_link', LinkUtil::absoluteLink(LinkUtil::link(array('journal_comment_moderation', $oComment->getActivationHash(), 'activate'), 'FileManager')));
						$oEmailContent->replaceIdentifier('deletion_link', LinkUtil::absoluteLink(LinkUtil::link(array('journal_comment_moderation', $oComment->getActivationHash(), 'delete'), 'FileManager')));
						$oEmail = new EMail("New comment on your journal entry ".$this->oEntry->getTitle(), $oEmailContent);
						$oSender = $this->oEntry->getUserRelatedByCreatedBy();
						$oEmail->addRecipient($oSender->getEmail(), $oSender->getFullName());
						$oEmail->send();
				}
				LinkUtil::redirect(LinkUtil::link($this->oEntry->getLink())."#comments");
			}
		}
		$oTemplate->replaceIdentifier('container', $this->renderAddComment($this->oEntry), $this->sContainerName);
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

	public function datesHidden() {
		return $this->bDatesHidden;
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
		return array('options' => $aResult, 'current' => $this->sContainerName, 'current_auxiliary' => $this->sAuxiliaryContainer);
	}
	
	public function listWidgets() {
		$aWidgetTypes = array();
		foreach(get_class_methods($this) as $sMethodName) {
			if(StringUtil::startsWith($sMethodName, 'render') && StringUtil::endsWith($sMethodName, 'Widget')) {
				$oWidget = new StdClass();
				$oWidget->name = StringUtil::deCamelize(substr($sMethodName, strlen('render'), -strlen('Widget')));
				$oWidget->current = in_array($oWidget->name, $this->aWidgets, true);
				$oWidget->title = StringUtil::makeReadableName($oWidget->name);
				$aWidgetTypes[] = $oWidget;
			}
		}
		return $aWidgetTypes;
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
		$this->oPage->updatePageProperty('blog_auxiliary_container', $aData['auxiliary_container']);
		$this->oPage->updatePageProperty('blog_comment_mode', $aData['comment_mode']);
		$this->oPage->updatePageProperty('blog_dates_hidden', isset($aData['dates_hidden']) ? 'true' : '');
		$aWidgets =  array();
		foreach($aData['widgets'] as $sWidgetName) {
			if($sWidgetName !== false) {
				$aWidgets[] = $sWidgetName;
			}
		}
		$this->oPage->updatePageProperty('blog_widgets', implode(',', $aWidgets));
		$this->updateFlagsFromProperties();
	}
}
