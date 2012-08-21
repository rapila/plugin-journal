<?php

require_once('htmlpurifier/HTMLPurifier.standalone.php');

class JournalPageTypeModule extends PageTypeModule {
	
	// options: on, off, moderated
	private $sCommentMode;
	
	// options: list, short, full view
	private $sOverviewMode;
	
	// journal
	private $iJournalId = null;
	
	// tags that are selected
	private $aTags = null;
	
	// main blog template
	private $sTemplateSet;
	
	// main container for overviews and journal entry detail
	private $sContainerName;
	
	// auxiliary container for widgets
	private $sAuxiliaryContainer;
	
	// show year, month, day virtual navigation items
	private $bDateNavigationItemsVisible;
	
	// widgets [recent entries, calendar, collapsible-date-tree]
	private $aWidgets;
	
	// virtual item year
	private $iYear = null;
	
	// virtual item month
	private $iMonth = null;
	
	// virtual item day
	private $iDay = null;
		
	// entries per overview page
	private $iEntriesPerPage = null;
	
	const ALLOWED_POINTER_PAGE = 'page';
	const ADD_FILTER = 'add_filter';
	const REMOVE_FILTER = 'remove_filter';
	const SESSION_FILTER_NAME = 'tag_filter';

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
		if($oNavigationItem instanceof VirtualNavigationItem) {
			$aData = $oNavigationItem->getData();
			if(is_array($aData)) {
				$this->iYear = @$aData[1];
				$this->iMonth = @$aData[2];
				$this->iDay = @$aData[3];
			} elseif($aData instanceof JournalEntry) {
				$this->iYear = $aData->getCreatedAt('Y');
				$this->iMonth = $aData->getCreatedAt('n');
				$this->iDay = $aData->getCreatedAt('j');
			}
		}
		$this->setFilters();
	}
	
	private function setFilters() {
		$this->aTags = Session::getSession()->getAttribute(self::SESSION_FILTER_NAME);
		if(isset($_REQUEST[self::ADD_FILTER]) && (!is_array($this->aTags) || !in_array($_REQUEST[self::ADD_FILTER], $this->aTags))) {
			$this->aTags[] = $_REQUEST[self::ADD_FILTER];
		}
		if(isset($_REQUEST[self::REMOVE_FILTER]) && in_array($_REQUEST[self::REMOVE_FILTER], $this->aTags)) {
			$mKey = array_search($_REQUEST[self::REMOVE_FILTER], $this->aTags);
			unset($this->aTags[$mKey]);
		}
		Session::getSession()->setAttribute(self::SESSION_FILTER_NAME, $this->aTags);
	}

	public function updateFlagsFromProperties() {
		$this->sOverviewMode = $this->oPage->getPagePropertyValue('blog_overview_action', 'list');
		$this->sCommentMode = $this->oPage->getPagePropertyValue('blog_comment_mode', 'on');
		$this->iJournalId = $this->oPage->getPagePropertyValue('blog_journal_id', null);
		$this->sTemplateSet = $this->oPage->getPagePropertyValue('blog_template_set', 'default');
		$this->sContainerName = $this->oPage->getPagePropertyValue('blog_container', 'content');
		$this->sAuxiliaryContainer = $this->oPage->getPagePropertyValue('blog_auxiliary_container', null);
		$this->iEntriesPerPage = $this->oPage->getPagePropertyValue('blog_entries_per_page', null);
		$this->bDateNavigationItemsVisible = !!$this->oPage->getPagePropertyValue('blog_date_navigation_items_visible', null);
		$this->aWidgets = $this->oPage->getPagePropertyValue('blog_widgets', '');
		if($this->aWidgets === '') {
			$this->aWidgets = array();
		} else {
			$this->aWidgets = explode(',', $this->aWidgets);
		}
	}
	
	public function setIsDynamicAndAllowedParameterPointers(&$bIsDynamic, &$aAllowedParams, $aModulesToCheck = null) {
		$bIsDynamic = true;
		$aAllowedParams = array(self::ALLOWED_POINTER_PAGE);
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
		$sMethod = "overview_$this->sOverviewMode";
		if($this->oNavigationItem instanceof VirtualNavigationItem) {
			$sMethod = substr($this->oNavigationItem->getType(), strlen('journal-'));
			if($this->oNavigationItem->getData() instanceof JournalEntry) {
				$this->oEntry = $this->oNavigationItem->getData();
			}
		}
		$sMethod = StringUtil::camelize("display_$sMethod");
		return $this->$sMethod($oTemplate);
	}
	
	private function displayOverviewList($oTemplate, $oQuery = null) {
		$this->renderJournalEntries($oQuery, $this->constructTemplate('list_entry'), $oTemplate);
	}
	
	private function displayOverviewTruncated($oTemplate, $oQuery = null) {
		$this->renderJournalEntries($oQuery, $this->constructTemplate('truncated_entry'), $oTemplate);
	}
	
	private function displayOverviewFull($oTemplate, $oQuery = null) {
		$this->renderJournalEntries($oQuery, $this->constructTemplate('short_entry'), $oTemplate);
	}
	
	private function addPagination(&$oQuery, $oTemplate) {
		if($this->iEntriesPerPage === null) {
			return;
		}		
		$iCountAll = $oQuery->count();
		$oQuery->limit($this->iEntriesPerPage);
		
		// Display pager if count all entries is larger then entries per page limit
		if($iCountAll <= $this->iEntriesPerPage) {
			return;
		}
		$iPage = (int) (isset($_REQUEST[self::ALLOWED_POINTER_PAGE]) ? $_REQUEST[self::ALLOWED_POINTER_PAGE] : 1);
		if($iPage > 1) {
			$iOffset = ($iPage-1)*$this->iEntriesPerPage;
			$oQuery->offset($iOffset);
		}
		$iPagesCount = (int) ceil($iCountAll/$this->iEntriesPerPage);
		$oPagerTemplate = $this->constructTemplate('pagination');
		
		// Prepare configurable next and previous page links
		$aPagerLinkTexts = Settings::getSetting('journal', 'pagination_link_text', null);
		$sPreviousLinkText = isset($aPagerLinkTexts['previous']) ? $aPagerLinkTexts['previous'] : '<';
		$sNextLinkText = isset($aPagerLinkTexts['next']) ? $aPagerLinkTexts['next'] : '>';

		// Previous link or placeholder
		if($iPage > 1) {
			$oPreviousLink = TagWriter::quickTag('a', array('title' => StringPeer::getString('pager.previous_page'), 'href' => LinkUtil::link($this->oPage->getLinkArray(self::ALLOWED_POINTER_PAGE,$iPage-1))), $sPreviousLinkText);
		} else {
			$oPreviousLink = TagWriter::quickTag('span', array(), $sPreviousLinkText);
		}
		$oPagerTemplate->replaceIdentifier('previous_link', $oPreviousLink);
		
		// Next link or placeholder
		if($iPage < $iPagesCount) {
			$oNextLink = TagWriter::quickTag('a', array('title' => StringPeer::getString('pager.next_page'), 'href' => LinkUtil::link($this->oPage->getLinkArray(self::ALLOWED_POINTER_PAGE, $iPage+1))), $sNextLinkText);
		} else {
			$oNextLink = TagWriter::quickTag('span', array(), $sNextLinkText);
		}
		$oPagerTemplate->replaceIdentifier('next_link', $oNextLink);
		
		// All page links including current one
		for($i = 1; $i <= $iPagesCount; $i++) {
			if($i === $iPage) {
				$oPageLink = TagWriter::quickTag('span', array(), $i);
			} else {
				$oPageLink = TagWriter::quickTag('a', array('title' => StringPeer::getString('pager.go_to_page', null, null, array('page_number' => $i)), 'href' => LinkUtil::link($this->oPage->getLinkArray(self::ALLOWED_POINTER_PAGE, $i))), $i);
			}
			$oPagerTemplate->replaceIdentifierMultiple('page_links', $oPageLink);
		}
		if($oPagerTemplate->hasIdentifier('page_count_info')) {
			$oPagerTemplate->replaceIdentifier('page_count_info', "$this->iEntriesPerPage/$iCountAll");
		}
		$oTemplate->replaceIdentifier('pagination', $oPagerTemplate);
	}

	private function renderJournalEntries(JournalEntryQuery $oQuery = null, Template $oEntryTemplatePrototype, Template $oFullTemplate, Template $oCommentTemplate = null, $sContainerName = null) {

		if($oQuery === null) {
			$oQuery = FrontendJournalEntryQuery::create();
		}
		if($sContainerName === null) {
			$sContainerName = $this->sContainerName;
		}
		if($this->iJournalId) {
			$oQuery->filterByJournalId($this->iJournalId);
		}
		if(!empty($this->aTags)) {
			$oQuery->filterByTagName($this->aTags);
		}
		$this->addPagination($oQuery, $oFullTemplate);

		foreach($oQuery->orderByCreatedAt(Criteria::DESC)->find() as $oEntry) {
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
		
		$sDetailLink = LinkUtil::link($oEntry->getLink($this->oPage), 'FrontendManager');
		$oEntryTemplate->replaceIdentifier('link', $sDetailLink);
		$oEntryTemplate->replaceIdentifier('detail_link_title', StringPeer::getString('journal_entry.add_comment_title', null, null, array('title' => $oEntry->getTitle())));
		
		if($oEntryTemplate->hasIdentifier('text')) {
			$oEntryTemplate->replaceIdentifier('text', RichtextUtil::parseStorageForFrontendOutput($oEntry->getText()));
		}
		if($oEntryTemplate->hasIdentifier('text_short')) {
			$oEntryTemplate->replaceIdentifier('text_short', RichtextUtil::parseStorageForFrontendOutput($oEntry->getTextShort()));
			if($oEntryTemplate->hasIdentifier('read_more_link')) {
				$oEntryTemplate->replaceIdentifier('read_more_link', $sDetailLink);
			}
		}
		if($this->oEntry !== null && $this->oEntry == $oEntry) {
			$oEntryTemplate->replaceIdentifier('current_class', ' class="current"', null, Template::NO_HTML_ESCAPE);
		}
		if($oEntryTemplate->hasIdentifier('tags')) {
			$aTagInstances = TagInstanceQuery::create()->filterByModelName('JournalEntry')->filterByTaggedItemId($oEntry->getId())->joinTag()->find();
			foreach($aTagInstances as $i => $oTagInstance) {
				if($i > 0) {
					$oEntryTemplate->replaceIdentifierMultiple('tags', ', ', null, Template::NO_NEWLINE|Template::NO_NEW_CONTEXT);			
				}
				$oEntryTemplate->replaceIdentifierMultiple('tags', $oTagInstance->getTag()->getReadableName(), null, Template::NO_NEW_CONTEXT|Template::NO_NEWLINE);			
			}
		}
		$oSession = Session::getSession();
		if($oSession->hasAttribute('has_new_comment')) {
			$oSession->resetAttribute('has_new_comment');
			$oEntryTemplate->replaceIdentifier('new_comment_thank_you_message', StringPeer::getString('journal_entry.new_comment_thank_you'));			
		}
		if($oEntryTemplate->hasIdentifier('journal_comments')) {
			$oEntryTemplate->replaceIdentifier('journal_comments', $this->renderComments($oEntry->getJournalComments($oCommentQuery), $oEntry));
		}
		if($oEntryTemplate->hasIdentifier('journal_gallery') && $oEntry->countJournalEntryImages() > 0) {
			$oEntryTemplate->replaceIdentifier('journal_gallery', $this->renderGallery($oEntry));
		}
		return $oEntryTemplate;
	}

	private function renderComments($aComments, JournalEntry $oEntry = null) {
		$oEntryTemplate = $this->constructTemplate('comments');
		$iCountComments = count($aComments);
		$oEntryTemplate->replaceIdentifier('comment_count', $iCountComments > 0 ? $iCountComments : StringPeer::getString('journal.comment_count.none'));
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
		$this->renderJournalEntries(FrontendJournalEntryQuery::create()->mostRecent(), $this->constructTemplate('list_entry'), $oTemplate, null, 'entries');
		return $oTemplate;
	}
	
	private function displayYear($oTemplate) {
		return $this->displayFilteredOverview($oTemplate);
	}
	
	private function displayMonth($oTemplate) {
		return $this->displayFilteredOverview($oTemplate);
	}
	
	private function displayDay($oTemplate) {
		return $this->displayFilteredOverview($oTemplate);
	}
	
	private function displayFilteredOverview($oTemplate) {
		$aFilterData = $this->oNavigationItem->getData();
		$oQuery = JournalEntryQuery::create();
		$oQuery->filterByDate($this->iYear, $this->iMonth, $this->iDay);
		$sMethodName = StringUtil::camelize("display_overview_$this->sOverviewMode");
		$this->$sMethodName($oTemplate, $oQuery);
	}
	
	private function displayEntry($oTemplate) {
		if($this->oEntry === null) {
			LinkUtil::redirect(LinkUtil::link($this->oPage->getLinkArray(), 'FrontendManager'));
		}
		$oTemplate->replaceIdentifier('container', $this->renderEntry($this->oEntry, $this->constructTemplate('full_entry')), $this->sContainerName);
	}

	// For adding comments
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
			$oFlash->checkForValue('comment_name', 'comment_name_required');
			$oComment->setEmail($_POST['comment_email']);
			$oFlash->checkForEmail('comment_email', 'comment_email_required');
			if(!FormFrontendModule::validateRecaptchaInput()) {
				$oFlash->addMessage('captcha_required');
			}
			$oPurifierConfig = HTMLPurifier_Config::createDefault();
			$oPurifierConfig->set('Cache.SerializerPath', MAIN_DIR.'/'.DIRNAME_GENERATED.'/'.DIRNAME_CACHES.'/purifier');
			$oPurifierConfig->set('HTML.Doctype', 'XHTML 1.0 Transitional');
			$oPurifierConfig->set('AutoFormat.AutoParagraph', true);
			$oPurifier = new HTMLPurifier($oPurifierConfig);
			$_POST['comment_text'] = $oPurifier->purify($_POST['comment_text']);
			$oComment->setText($_POST['comment_text']);
			$oFlash->checkForValue('comment_text', 'comment_required');
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
				$oSession = Session::getSession();
				$oSession->setAttribute('has_new_comment', true);
				LinkUtil::redirect(LinkUtil::link($this->oEntry->getLink())."#comments");
			}
		}
		$oTemplate->replaceIdentifier('container', $this->renderAddComment($this->oEntry), $this->sContainerName);
	}
	
	// Override from parent
	protected function constructTemplate($sTemplateName = null, $bForceGlobalTemplatesDir = false) {
		if($this->sTemplateSet) {
			try {
				return parent::constructTemplate($sTemplateName, array(DIRNAME_MODULES, self::getType(), $this->getModuleName(), 'templates', $this->sTemplateSet));
			} catch (Exception $e) {}
		}
		return parent::constructTemplate($sTemplateName, array(DIRNAME_MODULES, self::getType(), $this->getModuleName(), 'templates', 'default'));
	}

 /**
	* renderTagCloudWidget()
	* 
	* description: 
	* display tags with variable font-size, @see config.yml section journal tag_cloud [pixel_size_min, pixel_size_max]
	* 
	* @return Template object / null
	*/	
	private function renderTagCloudWidget() {
		$aTags = TagQuery::create()->orderByName()->withTagInstanceCountFilteredByModel('JournalEntry')->find()->toKeyValue('Name', 'TagInstanceCount');

		if(empty($aTags)) {
			return null;
		}
		// Configure display style of cloud
		$bUseSizes = false;
		$iMinPixelFontSize = null;
		$iMaxPixelFontSize = null;
		$aSizeParams = Settings::getSetting('journal', 'tag_cloud', null);
		if(isset($aSizeParams['pixel_size_min'])) {
			$iMinPixelFontSize = $aSizeParams['pixel_size_min'];
		}
		if(isset($aSizeParams['pixel_size_max'])) {
			$iMaxPixelFontSize = $aSizeParams['pixel_size_max'];
		}
		if($iMinPixelFontSize !== null && $iMaxPixelFontSize !== null) {
			$bUseSizes = true;
		}
		// Calculate font sizes
		if($bUseSizes) {
			$iMinCount = min($aTags);
			$iMaxCount = max($aTags);
			$iFactor = 1;
			if($iMaxCount > $iMinCount) {
				$iFactor = $iMaxCount - $iMinCount;
			}
			$iPixelStep = ($iMaxPixelFontSize - $iMinPixelFontSize) / $iFactor;
		}
		// Render tags
		$oTemplate = $this->constructTemplate('tag');
		$oItemPrototype = $this->constructTemplate('tag_item');
		$sLabelEntry = StringPeer::getString('wns.');
		foreach($aTags as $sName => $iCount) {
			$oItemTemplate = clone $oItemPrototype;
			if($bUseSizes) {
				$iFontSize = (int) ceil($iMinPixelFontSize + (($iCount - $iMinCount) * $iPixelStep));
				$oItemTemplate->replaceIdentifier('size_style', ' style="font-size:'.$iFontSize.'px;line-height:'.ceil($iFontSize * 1.2).'px;"', null, Template::NO_HTML_ESCAPE);
			}
			if(is_array($this->aTags) && in_array($sName, $this->aTags)) {
				$oItemTemplate->replaceIdentifier('class_active', ' active');
				$oItemTemplate->replaceIdentifier('tag_link_title', StringPeer::getString('tag_link_title.remove'));
				$oItemTemplate->replaceIdentifier('tag_link', LinkUtil::link($this->oPage->getLinkArray(), null, array(self::REMOVE_FILTER => $sName)));
			} else {
				$oItemTemplate->replaceIdentifier('tag_link', LinkUtil::link($this->oPage->getLinkArray(), null, array(self::ADD_FILTER => $sName)));
				$oItemTemplate->replaceIdentifier('tag_link_title', StringPeer::getString('tag_link_title.add', null, null, array('tagname' => StringUtil::makeReadableName($sName)), true));
			}
			$oItemTemplate->replaceIdentifier('tag_name', ucfirst(StringPeer::getString('tag.'.$sName, null, $sName)));
			$oTemplate->replaceIdentifierMultiple('tag_item', $oItemTemplate);
		}
		return $oTemplate;
	}

 /**
	* renderCalendarWidget()
	* 
	* description: display calendar
	* • as calender (date_picker), include javascript file web/js/journal-collapsible-date-tree.js
	* • as collapsible date tree, include javascript file web/js/calendar.js
	* 
	* @return Template object
	*/	
	private function renderCalendarWidget() {
		
		$oQuery = FrontendJournalEntryQuery::create()->distinct()->filterByJournalId($this->iJournalId)->clearSelectColumns();
		$oQuery->withColumn('DAY('.JournalEntryPeer::CREATED_AT.')', 'Day');
		$oQuery->withColumn('MONTH('.JournalEntryPeer::CREATED_AT.')', 'Month');
		$oQuery->withColumn('YEAR('.JournalEntryPeer::CREATED_AT.')', 'Year');
		$aResult = $oQuery->orderByYearMonthDay()->select('Year', 'Month', 'Day')->find();
		
		$oTemplate = $this->constructTemplate('calendar');
		$oYearPrototype = $this->constructTemplate('calendar_item_year');
		$oMonthPrototype = $this->constructTemplate('calendar_item_month');
		$oDayPrototype = $this->constructTemplate('calendar_item_day');
		$sPreviousYear = null;
		$sPreviousMonth = null;
		$oYearTemplate = null;
		$oMonthTemplate = null;

		foreach($aResult as $aDate) {
			// Make month template whenever month changes (or year, because it can happen that two months are the same when a year changes) 
			// Add it to year template
			if($aDate['Year'] !== $sPreviousYear || $aDate['Month'] !== $sPreviousMonth) {
				$sPreviousMonth = $aDate['Month'];
				if($oMonthTemplate) {
					$oYearTemplate->replaceIdentifierMultiple('month_item', $oMonthTemplate);	
				}
				$oMonthTemplate = clone $oMonthPrototype;
				$oMonthTemplate->replaceIdentifier('year', $aDate['Year']);
				$oMonthTemplate->replaceIdentifier('month', $aDate['Month']);
				$oMonthTemplate->replaceIdentifier('class_is_active', $aDate['Month'] === $this->iMonth ? ' is_active' : '');
				LocaleUtil::getLocaleId();
				$sMonthName = strftime( '%B', mktime( 0, 0, 0, $aDate['Month'], 1, $aDate['Year']));
				$oMonthTemplate->replaceIdentifier('month_name', $sMonthName);
				$oMonthTemplate->replaceIdentifier('link', LinkUtil::link($this->oPage->getLinkArray($aDate['Year'], $aDate['Month'])));
			}
			
			// Make year template whenever the year changes and add it to main template
			if($aDate['Year'] !== $sPreviousYear) {
				$sPreviousYear = $aDate['Year'];
				if($oYearTemplate) {
					$oTemplate->replaceIdentifierMultiple('calendar_item', $oYearTemplate);	
				}
				$oYearTemplate = clone $oYearPrototype;
				$oYearTemplate->replaceIdentifier('year', $aDate['Year']);
				if($aDate['Year'] === $this->iYear) {
					$oYearTemplate->replaceIdentifier('class_is_active', ' is_active');
					$oYearTemplate->replaceIdentifier('display', 'block');
				} else {
					$oYearTemplate->replaceIdentifier('display', 'none');
				}
				$oYearTemplate->replaceIdentifier('link', LinkUtil::link($this->oPage->getLinkArray($aDate['Year'])));
			}
			
			if(!$oMonthTemplate->hasIdentifier('day_item')) {
				continue;
			}
			
			// Make day item template and add it to month template
			$oDayTemplate = clone $oDayPrototype;
			$oDayTemplate->replaceIdentifier('year', $aDate['Year']);
			$oDayTemplate->replaceIdentifier('month', $aDate['Month']);
			$oDayTemplate->replaceIdentifier('day', $aDate['Day']);
			$oDayTemplate->replaceIdentifier('link', LinkUtil::link($this->oPage->getLinkArray($aDate['Year'], $aDate['Month'], $aDate['Day'])));
			$oMonthTemplate->replaceIdentifierMultiple('day_item', $oDayTemplate);
		}
		
		if($oMonthTemplate) {
			$oYearTemplate->replaceIdentifierMultiple('month_item', $oMonthTemplate);	
		}
		if($oYearTemplate) {
			$oTemplate->replaceIdentifierMultiple('calendar_item', $oYearTemplate);	
		}
		return $oTemplate;
	}
	

 /**
	* renderGallery()
	* 
	* description: display image gallery
	* 
	* @return Template object
	*/	
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






	
	/*
		***** Journal config and journal entry admin methods *****
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

	public function currentEntriesPerPage() {
		return $this->iEntriesPerPage;
	}

	public function currentJournal() {
		return $this->iJournalId;
	}

	public function datesHidden() {
		return $this->bDateNavigationItemsVisible;
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
			$aResult[$oSet->getFileName()] = StringPeer::getString('journal.template_'.$oSet->getFileName(), null, StringUtil::makeReadableName($oSet->getFileName()));
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
		$aWidgetTypesOrdered = array();
		foreach(get_class_methods($this) as $sMethodName) {
			if(StringUtil::startsWith($sMethodName, 'render') && StringUtil::endsWith($sMethodName, 'Widget')) {
				$oWidget = new StdClass();
				$oWidget->name = StringUtil::deCamelize(substr($sMethodName, strlen('render'), -strlen('Widget')));
				$iLocation = array_search($oWidget->name, $this->aWidgets, true);
				$oWidget->current = $iLocation !== false;
				$oWidget->title = StringPeer::getString('journal_config.'.$oWidget->name, null, StringUtil::makeReadableName($oWidget->name));
				if($oWidget->current) {
					$aWidgetTypesOrdered[$iLocation] = $oWidget;
				} else {
					$aWidgetTypes[] = $oWidget;
				}
			}
		}
		ksort($aWidgetTypesOrdered);
		$aWidgetTypes = array_merge($aWidgetTypesOrdered, $aWidgetTypes);
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
		$this->oPage->updatePageProperty('blog_journal_id', $this->iJournalId);
		$this->oPage->updatePageProperty('blog_entries_per_page', $aData['blog_entries_per_page'] == '' ? null : $aData['blog_entries_per_page']);
		$this->oPage->updatePageProperty('blog_template_set', $aData['template_set']);
		$this->oPage->updatePageProperty('blog_container', $aData['container']);
		$this->oPage->updatePageProperty('blog_auxiliary_container', $aData['auxiliary_container']);
		$this->oPage->updatePageProperty('blog_comment_mode', $aData['comment_mode']);
		$this->oPage->updatePageProperty('blog_date_navigation_items_visible', isset($aData['dates_hidden']) ? 'true' : '');
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
