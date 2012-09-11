<?php

require_once('htmlpurifier/HTMLPurifier.standalone.php');

class JournalPageTypeModule extends PageTypeModule {
	
	// Comment mode options: [on, off, moderated]
	private $sCommentMode;
	
	// Overview mode options: [list, truncated, full]
	private $sOverviewMode;
	
	// Journal id
	private $iJournalId = null;
	
	// Tags selected
	private $aTags = null;
	
	// Main blog template
	private $sTemplateSet;
	
	// Main container for overviews and journal entry detail
	private $sContainerName;
	
	// Auxiliary container for widgets
	private $sAuxiliaryContainer;
	
	// Show year, month, day virtual navigation items
	private $bDateNavigationItemsVisible;
	
	// Anti spam check with Captcha
	private $bCaptchaEnabled;
	
	// Widgets [recent entries, calendar, collapsible-date-tree]
	private $aWidgets;
	
	// Virtual item year
	private $iYear = null;
	
	// Virtual item month
	private $iMonth = null;
	
	// Virtual item day
	private $iDay = null;
		
	// Entries per overview page
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
		$this->bDateNavigationItemsVisible = !!$this->oPage->getPagePropertyValue('blog_date_navigation_items_visible', false);
		$this->bCaptchaEnabled = !!$this->oPage->getPagePropertyValue('blog_captcha_enabled', true);
		
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
		$iPage = (int) (isset($_REQUEST[self::ALLOWED_POINTER_PAGE]) ? $_REQUEST[self::ALLOWED_POINTER_PAGE] : 1);
		$oPager = new SimplePager($oQuery, $iPage, $this->iEntriesPerPage);
		if($oPager->requiresPagination() === false) {
			return;
		}
		
		// Basic page link without page number
		$sBasePageLink = LinkUtil::link(FrontendManager::$CURRENT_NAVIGATION_ITEM->getLink()).'/'.self::ALLOWED_POINTER_PAGE.'/';
		$oPager->setPageLinkBase($sBasePageLink);
		$oQuery = $oPager->getQuery();

		$oPagerTemplate = $this->constructTemplate('pagination');
		
		// All page links including current one
		$iTotalPages = $oPager->getTotalPageCount();
		for($i = 1; $i <= $iTotalPages; $i++) {
			if($i === $iPage) {
				$oPageLink = TagWriter::quickTag('span', array(), $i);
			} else {
				$oPageLink = TagWriter::quickTag('a', array('title' => StringPeer::getString('pager.go_to_page', null, null, array('page_number' => $i)), 'href' => LinkUtil::link($this->oPage->getLinkArray(self::ALLOWED_POINTER_PAGE, $i))), $i);
			}
			$oPagerTemplate->replaceIdentifierMultiple('page_links', $oPageLink);
		}
		$oPagerTemplate->replaceIdentifier('previous_link', $oPager->getPreviousLink());
		$oPagerTemplate->replaceIdentifier('next_link', $oPager->getNextLink());
		$oTemplate->replaceIdentifier('pagination', $oPagerTemplate);
		return $oPager;
	}

	// Convenience function for external uses
	public static function renderEntries(JournalEntryQuery $oQuery, Template $oEntryTemplate) {
		$oTemplate = new Template(TemplateIdentifier::constructIdentifier('container', 'entries'), null, true);
		$oModule = new JournalPageTypeModule();
		$oModule->renderJournalEntries($oQuery, $oEntryTemplate, $oTemplate, null, 'entries');
		return $oTemplate;
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
		$oEntryTemplate->replaceIdentifier('link', LinkUtil::absoluteLink($sDetailLink), null, LinkUtil::isSSL());
		$oEntryTemplate->replaceIdentifier('detail_link_title', StringPeer::getString('journal_entry.add_comment_title', null, null, array('title' => $oEntry->getTitle())));

		if($oEntryTemplate->hasIdentifier('text')) {
			$oEntryTemplate->replaceIdentifier('text', RichtextUtil::parseStorageForFrontendOutput($oEntry->getText()));
		}
		if($oEntryTemplate->hasIdentifier('text_short')) {
			$oEntryTemplate->replaceIdentifier('text_short', RichtextUtil::parseStorageForFrontendOutput($oEntry->getTextShort()));
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
			case "notified":
			case "on":
				if($this->bCaptchaEnabled) {
					$oLeaveCommentTemplate->replaceIdentifier('captcha', FormFrontendModule::getRecaptchaCode('journal_comment'));
				}
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
			LinkUtil::redirect(LinkUtil::link($this->oEntry->getLink()));
		}
		if(Manager::isPost() && isset($_POST['preview'])) {
			$oComment = $_POST['preview'];
			$oTemplate->replaceIdentifier('container', $this->renderComments(array($oComment), $this->oEntry), $this->sContainerName);
			return;
		}
		$oTemplate->replaceIdentifier('container', $this->renderAddComment($this->oEntry), $this->sContainerName);
	}
	
	// Override from parent
	protected function constructTemplate($sTemplateName = null, $bForceGlobalTemplatesDir = false) {
		return self::template($sTemplateName, $this->sTemplateSet);
	}
	
	public static function template($sTemplateName, $sTemplateSet = null) {
		if($sTemplateSet) {
			try {
				return new Template($sTemplateName, array(DIRNAME_MODULES, self::getType(), self::moduleName(), 'templates', $sTemplateSet));
			} catch (Exception $e) {}
		}
		return new Template($sTemplateName, array(DIRNAME_MODULES, self::getType(), self::moduleName(), 'templates', 'default'));
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
		// get all tags related to
		// • model JournalEntry
		// • active journal_enties with current journal_id 
		$aIncludeJournalEntryIds = FrontendJournalEntryQuery::create()->filterByJournalId($this->iJournalId)->select('Id')->find()->getData();
		$oQuery = TagQuery::create()->orderByName()->withTagInstanceCountFilteredByModel('JournalEntry', $aIncludeJournalEntryIds);
		$aTags = $oQuery->find()->toKeyValue('Name', 'TagInstanceCount');
		
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
	* description: display calendar (date_picker)
	* include javascript file web/js/calendar.js
	* @return Template object
	*/	
	private function renderCalendarWidget() {
		$aResult = FrontendJournalEntryQuery::create()->filterByJournalId($this->iJournalId)->distinctDates()->find();
		$oTemplate = $this->constructTemplate('widget_calendar');
		$oItemPrototype = $this->constructTemplate('widget_calendar_item');
		foreach($aResult as $aDate) {			
			// Make day item template and add it to month template
			$oItemTemplate = clone $oItemPrototype;
			foreach($aDate as $sPeriod => $sValue) {
				$oItemTemplate->replaceIdentifier(strtolower($sPeriod), $sValue);
			}
			$oItemTemplate->replaceIdentifier('link', LinkUtil::link($this->oPage->getLinkArray($aDate['Year'], $aDate['Month'], $aDate['Day'])));
			$oTemplate->replaceIdentifierMultiple('calendar_item', $oItemTemplate);
		}
		return $oTemplate;
	}
	
	private function renderTreeWidget() {
		$iTreeWidgetLevels = Settings::getSetting('journal', 'display_journal_tree_levels', 2);
		
		$aResult = FrontendJournalEntryQuery::create()->filterByJournalId($this->iJournalId)->distinctDates()->find();
		
		$oTemplate = $this->constructTemplate('widget_tree');
		$oItemPrototype = $this->constructTemplate('widget_tree_item');
		
		$sPreviousYear = null;
		$sPreviousMonth = null;
		
		$aStack = array($oTemplate);
		
		$cReduceToLevel = function($iLevel) use (&$aStack) {
			while(count($aStack) > $iLevel) {
				$oPreviousTemplate = array_pop($aStack);
				$oCurrentTemplate = $aStack[count($aStack)-1];
				$oCurrentTemplate->replaceIdentifierMultiple('items', $oPreviousTemplate, null, Template::NO_NEW_CONTEXT);
			}
		};
		
		$oPage = $this->oPage;
		$cOutput = function($aDate, $sFormat) use (&$aStack, $oItemPrototype, $oPage) {
			$oTemplate = clone $oItemPrototype;
			array_push($aStack, $oTemplate);
			foreach($aDate as $sPeriod => $sValue) {
				$oTemplate->replaceIdentifier(strtolower($sPeriod), $sValue);
			}
			$oDate = new DateTime();
			$oDate->setDate($aDate['Year'], @$aDate['Month'] ? $aDate['Month'] : 1, @$aDate['Day'] ? @$aDate['Day'] : 1);
			$oTemplate->replaceIdentifier('full_name', LocaleUtil::localizeDate($oDate, null, $sFormat));
			$aKeys = array_keys($aDate);
			$oTemplate->replaceIdentifier('name', $aDate[$aKeys[count($aKeys)-1]]);
			$oTemplate->replaceIdentifier('level', count($aKeys));
			$oTemplate->replaceIdentifier('link', LinkUtil::link($oPage->getFullPathArray(array_values($aDate))));
		};
		
		foreach($aResult as $aDate) {
			$oCurrentTemplate = null;
			
			// Make year template whenever the year changes and add it to main template
			if($aDate['Year'] !== $sPreviousYear) {
				$cReduceToLevel(1);
				$sPreviousYear = $aDate['Year'];
				$cOutput(array('Year' => $aDate['Year']), 'Y');
			}
			
			// Render 2nd level months
			if($iTreeWidgetLevels === 1) continue;
			// Make month template whenever month changes (or year, because it can happen that two months are the same when a year changes) 
			// Add it to year template
			if($aDate['Year'] !== $sPreviousYear || $aDate['Month'] !== $sPreviousMonth) {
				$cReduceToLevel(2);
				$sPreviousMonth = $aDate['Month'];
				$cOutput(array('Year' => $aDate['Year'], 'Month' => $aDate['Month']), 'B');
			}
			
			// Render 3rd level days
			if($iTreeWidgetLevels === 2) continue;
			$cReduceToLevel(3);
			$cOutput(array('Year' => $aDate['Year'], 'Month' => $aDate['Month'], 'Day' => $aDate['Day']), 'x');
		}
		
		$cReduceToLevel(1);
		
		return $oTemplate;
	}

 /**
	* renderRssFeedWidget()
	* 
	* description: display rss feed link
	* requires rss_feed_journal_page_name in config.yml section journal
	* @return Template object
	*/	
	public function renderRssFeedWidget() {
		$mJournalPageName = Settings::getSetting('journal', 'rss_feed_journal_page_name', 'blog');
		$oJournalPage = null;
		$sPageName = null; 
		if(!is_array($mJournalPageName)) {
			$sPageName = $mJournalPageName;
		} else if(isset($mJournalPageName[$this->iJournalId])) {
			$sPageName = $mJournalPageName[$this->iJournalId];
		}
		$oJournalPage = PageQuery::create()->filterByName($sPageName)->findOne();
		if($oJournalPage === null) {
			throw new Exception ('Error in JournalPageTypeModule::renderRssFeedWidget(): journal page name is not properly configured');
		}
		$oTemplate = $this->constructTemplate('widget_rss_feed');
		$oTemplate->replaceIdentifier('journal_feed_link', LinkUtil::link($oJournalPage->getFullPathArray()));
		if($sTitle = StringPeer::getString('wns.journal.rss_feed_title}}')) {
			$oTemplate->replaceIdentifier('journal_feed_link_title', ' title="'.$sTitle.'"', null, Template::NO_HTML_ESCAPE);
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
		********************************************************************************************************************************** 
	*/
	public function detailWidget() {
		$oWidget = WidgetModule::getWidget('journal_entry_detail', null, $this->oPage);
		return $oWidget->getSessionKey();
	}
	
	public function currentOverviewMode() {
		return $this->sOverviewMode;
	}

	public function currentCommentMode() {
		return $this->sCommentMode;
	}

	public function currentEntriesPerPage() {
		return $this->iEntriesPerPage;
	}

	public function currentJournalId() {
		return $this->iJournalId;
	}

	public function dateNavigationItemsVisible() {
		return $this->bDateNavigationItemsVisible;
	}

	public function captchaEnabled() {
		return $this->bCaptchaEnabled;
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
				$oWidget->current = in_array($oWidget->name, $this->aWidgets, true);
				$oWidget->title = StringPeer::getString('journal_config.'.$oWidget->name, null, StringUtil::makeReadableName($oWidget->name));
				if($oWidget->current) {
					$iKey = array_search($oWidget->name, $this->aWidgets);
					if($iKey !== false) {
						$aWidgetTypesOrdered[$iKey] = $oWidget;
					} else {
						$aWidgetTypes[] = $oWidget;
					}
				} else {
					$aWidgetTypes[] = $oWidget;
				}
			}
		}
		$aWidgetTypes = array_merge($aWidgetTypesOrdered, $aWidgetTypes);
		return $aWidgetTypes;
	}

	public function journalProperties() {
		if($this->iJournalId === CriteriaListWidgetDelegate::SELECT_WITHOUT) {
			return null;
		}
		$oJournal = JournalQuery::create()->findPk($this->iJournalId);
		if($oJournal) {
			return $oJournal->toArray();
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
		// @todo this method is called from the journalPageTypeModule journal select and intended to create a new journal
		// is this some fallback stuff for migrating old journal_entries without journal_ids???
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
		$oJournal = JournalQuery::create()->findPk($this->iJournalId);
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
		$this->oPage->updatePageProperty('blog_entries_per_page', $aData['entries_per_page'] == '' ? null : $aData['entries_per_page']);
		$this->oPage->updatePageProperty('blog_template_set', $aData['template_set']);
		$this->oPage->updatePageProperty('blog_container', $aData['container']);
		$this->oPage->updatePageProperty('blog_auxiliary_container', $aData['auxiliary_container']);
		$this->oPage->updatePageProperty('blog_comment_mode', $aData['comment_mode']);
		$this->oPage->updatePageProperty('blog_date_navigation_items_visible', $aData['date_navigation_items_visible'] ? 1 : 0);
		$this->oPage->updatePageProperty('blog_captcha_enabled', $aData['captcha_enabled'] ? 1 : 0);
		
		
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
