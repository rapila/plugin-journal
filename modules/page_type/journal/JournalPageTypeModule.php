<?php
/**
 * @package modules.page_type
 * @subpackage journal-plugin
 */
class JournalPageTypeModule extends PageTypeModule {
	
	// Overview mode options: [list, truncated, full]
	private $sOverviewMode;
	
	// Single or multiple journal ids
	private $aJournalIds = null;
	
	// Tags filter 
	private $aFilteredTags = array();
	
	// Journals filter
	private $aFilteredJournalIds = null;
	
	// Main blog template
	private $sTemplateSet;
	
	// Main container for overviews and journal entry detail
	private $sContainer;
	
	// Auxiliary container for widgets
	private $sAuxiliaryContainer;
	
	// Show year, month, day virtual navigation items
	private $bDateNavigationItemsVisible;
	
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

	// Names of filters and sessions
	const ADD_TAG = 'add_tag';
	const REMOVE_TAG = 'remove_tag';
	const RESET_TAGS = 'reset_tags';
	const ADD_JOURNAL = 'add_journal';
	const REMOVE_JOURNAL = 'remove_journal';
	const RESET_JOURNALS = 'reset_journals';
	
	const SESSION_TAG_FILTER = 'tag_filter';
	const SESSION_JOURNAL_FILTER = 'journal_filter';
	const SESSION_LAST_OVERVIEW_ITEM_LINK = 'last_overview_link';

	/**
	 * @var JournalEntry the entry to be viewed
	 */
	private $oEntry;
	
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
				$this->iYear = $aData->getPublishAt('Y');
				$this->iMonth = $aData->getPublishAt('n');
				$this->iDay = $aData->getPublishAt('j');
			}
		}
	}

	public function updateFlagsFromProperties() {
		$this->sOverviewMode = $this->oPage->getPagePropertyValue('journal:overview_action', 'list');
		$this->aJournalIds = explode(',', $this->oPage->getPagePropertyValue('journal:journal_id', ''));
		$this->sTemplateSet = $this->oPage->getPagePropertyValue('journal:template_set', 'default');
		$this->sContainer = $this->oPage->getPagePropertyValue('journal:container', 'content');
		$this->sAuxiliaryContainer = $this->oPage->getPagePropertyValue('journal:auxiliary_container', null);
		$this->iEntriesPerPage = $this->oPage->getPagePropertyValue('journal:entries_per_page', null);
		$this->bDateNavigationItemsVisible = !!$this->oPage->getPagePropertyValue('journal:date_navigation_items_visible', false);

		$this->aWidgets = $this->oPage->getPagePropertyValue('journal:widgets', '');
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
	
	private function setFilters() {
		$this->aFilteredTags = Session::getSession()->getAttribute(self::SESSION_TAG_FILTER);
		$this->aFilteredJournalIds = Session::getSession()->getAttribute(self::SESSION_JOURNAL_FILTER);
		
		// Intial or reset tags
		if($this->aFilteredTags === null || isset($_REQUEST[self::RESET_TAGS])) {
			$this->aFilteredTags = array();
		}
		// Initial or reset journals
		if($this->aFilteredJournalIds === null || isset($_REQUEST[self::RESET_JOURNALS])) {
			$this->aFilteredJournalIds = $this->aJournalIds;
		}
		// Add and remove tag filters
		if(isset($_REQUEST[self::ADD_TAG]) && !in_array($_REQUEST[self::ADD_TAG], $this->aFilteredTags)) {
			$this->aFilteredTags[] = $_REQUEST[self::ADD_TAG];
		}
		if(isset($_REQUEST[self::REMOVE_TAG])) {
			$mKey = array_search($_REQUEST[self::REMOVE_TAG], $this->aFilteredTags);
			if($mKey !== false) {
				unset($this->aFilteredTags[$mKey]);
			}
		}
		// Add and remove journals filters
		if(isset($_REQUEST[self::ADD_JOURNAL]) && !in_array($_REQUEST[self::ADD_JOURNAL], $this->aFilteredJournalIds) && in_array($_REQUEST[self::ADD_JOURNAL], $this->aJournalIds)) {
			$this->aFilteredJournalIds[] = $_REQUEST[self::ADD_JOURNAL];
		}
		if(isset($_REQUEST[self::REMOVE_JOURNAL])) {
			$mKey = array_search($_REQUEST[self::REMOVE_JOURNAL], $this->aFilteredJournalIds);
			if($mKey !== false) {
				unset($this->aFilteredJournalIds[$mKey]);
			}
		}
		// Write filter sessions
		Session::getSession()->setAttribute(self::SESSION_JOURNAL_FILTER, $this->aFilteredJournalIds);
		Session::getSession()->setAttribute(self::SESSION_TAG_FILTER, $this->aFilteredTags);
	}
	
	public function display(Template $oTemplate, $bIsPreview = false) {
		$this->setFilters();
		$this->fillAuxilliaryContainers($oTemplate);
		if(!$oTemplate->hasIdentifier('container', $this->sContainer)) {
			return;
		}
		if($bIsPreview) {
			$oTag = TagWriter::quickTag('div', array('id' => 'journal_contents', 'class' => 'filled-container editing page-type-journal'));
			$oTemplate->replaceIdentifier('container', $oTag, $this->sContainer);
			return;
		}
		$sMethod = "overview_$this->sOverviewMode";
		if($this->oNavigationItem instanceof VirtualNavigationItem) {
			$sMethod = substr($this->oNavigationItem->getType(), strlen('journal-'));
			if($this->oNavigationItem->getData() instanceof JournalEntry) {
				$this->oEntry = $this->oNavigationItem->getData();
			}
		}
		// whenever there is no detail requested, store current navigation item for return_to_list_view link
		if($this->oEntry === null) {
			Session::getSession()->setAttribute(self::SESSION_LAST_OVERVIEW_ITEM_LINK, $this->oNavigationItem->getLink());
		}
		$sMethod = StringUtil::camelize("display_$sMethod");
		return $this->$sMethod($oTemplate);
	}
	
	private function displayOverviewList($oTemplate, $oQuery = null) {
		$oListTemplate = $this->constructTemplate('overview_list');
		$oListTemplate->replaceIdentifier('overview_type', 'list');
		$this->renderJournalEntries($oQuery, $this->constructTemplate('list_entry'), $oListTemplate, null, null, 'items');
		$oTemplate->replaceIdentifier('container', $oListTemplate, $this->sContainer);
	}
	
	private function displayOverviewTruncated($oTemplate, $oQuery = null) {
		$oListTemplate = $this->constructTemplate('overview_list');
		$oListTemplate->replaceIdentifier('overview_type', 'truncated');
		$this->renderJournalEntries($oQuery, $this->constructTemplate('truncated_entry'), $oListTemplate, null, null, 'items');
		$oTemplate->replaceIdentifier('container', $oListTemplate, $this->sContainer);
	}
	
	private function displayOverviewFull($oTemplate, $oQuery = null) {
		$oListTemplate = $this->constructTemplate('overview_list');
		$oListTemplate->replaceIdentifier('overview_type', 'short');
		$this->renderJournalEntries($oQuery, $this->constructTemplate('short_entry'), $oListTemplate, null, null, 'items');
		$oTemplate->replaceIdentifier('container', $oListTemplate, $this->sContainer);
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
		$sBasePageLink = LinkUtil::link(array_merge(FrontendManager::$CURRENT_NAVIGATION_ITEM->getLink(), array(self::ALLOWED_POINTER_PAGE)));
		$oPager->setPageLinkBase($sBasePageLink);
		$oQuery = $oPager->getQuery();

		$oPagerTemplate = $this->constructTemplate('pagination');
		
		// All page links including current one
		$iTotalPages = $oPager->getTotalPageCount();
		for($i = 1; $i <= $iTotalPages; $i++) {
			if($i === $iPage) {
				$oPageLink = TagWriter::quickTag('span', array(), $i);
			} else {
				$oPageLink = TagWriter::quickTag('a', array('title' => StringPeer::getString('pager.go_to_page', null, null, array('page_number' => $i)), 'href' => $oPager->getPageLink($i)), $i);
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

	private function renderJournalEntries(JournalEntryQuery $oQuery = null, Template $oEntryTemplatePrototype, Template $oFullTemplate, Template $oCommentTemplate = null, $sContainer = null, $sIdentifier = null) {
		if($oQuery === null) {
			$oQuery = FrontendJournalEntryQuery::create();
		}
		if($sIdentifier === null) {
			$sIdentifier = 'container';
			if($sContainer === null) {
				$sContainer = $this->sContainer;
			}
		}
		if(null !== $this->aFilteredJournalIds) {
			$oQuery->filterByJournalId($this->aFilteredJournalIds);
		} else {
			if($this->aJournalIds) {
				$oQuery->filterByJournalId($this->aJournalIds);
			}
		}
		if(!empty($this->aFilteredTags)) {
			$oQuery->filterByTagName($this->aFilteredTags);
		}
		$this->addPagination($oQuery, $oFullTemplate);
		$aEntries = $oQuery->orderByPublishAt(Criteria::DESC)->find();
		if(count($aEntries) === 0) {
			$oFullTemplate->replaceIdentifier('no_result_info', $this->renderNoResult());
			return;
		}
		foreach($oQuery->orderByPublishAt(Criteria::DESC)->find() as $oEntry) {
			$oFullTemplate->replaceIdentifierMultiple($sIdentifier, $this->renderEntry($oEntry, clone $oEntryTemplatePrototype), $sContainer);
		}
	}
	
	private function renderNoResult() {
		$oTemplate = $this->constructTemplate('no_result');
		if($this->tagFilterIsActive()) {
			$oTemplate->replaceIdentifierMultiple('search_information', TagWriter::quickTag('li', array(), StringPeer::getString('journal_entries.no_result.tags')), null);
		}
		if($this->journalFilterIsActive()) {
			$oTemplate->replaceIdentifierMultiple('search_information', TagWriter::quickTag('li', array(), StringPeer::getString('journal_entries.no_result.journals')));
		}
		if($this->archiveIsActive()) {
			$oTemplate->replaceIdentifierMultiple('search_information', TagWriter::quickTag('li', array(), StringPeer::getString('journal_entries.no_result.archive')));
		}
		if($this->tagFilterIsActive() || $this->journalFilterIsActive() || $this->archiveIsActive()) {
			$oTemplate->replaceIdentifierMultiple('search_information_note', TagWriter::quickTag('p', array(), StringPeer::getString('journal_entries.no_result.reset_filter_note')));
		}
		return $oTemplate;
	}
	
	private function journalFilterIsActive() {
		return count($this->aFilteredJournalIds) < count($this->aJournalIds);
	}
	
	private function tagFilterIsActive() {
		return !empty($this->aFilteredTags);
	}
	
	private function archiveIsActive() {
		return !is_null($this->iYear) || !is_null($this->iMonth) || !is_null($this->iDay);
	}

	private function renderEntry(JournalEntry $oEntry, Template $oEntryTemplate) {
		$oCommentQuery = JournalCommentQuery::create()->excludeUnverified();
		$oEntryTemplate->replaceIdentifier('journal_title', $oEntry->getJournal()->getName());
		$oEntryTemplate->replaceIdentifier('slug', $oEntry->getSlug());
		$oEntryTemplate->replaceIdentifier('name', $oEntry->getSlug());
		$oEntryTemplate->replaceIdentifier('user_name', $oEntry->getUserRelatedByCreatedBy()->getFullName());
		$oEntryTemplate->replaceIdentifier('id', $oEntry->getId());
		$oEntryTemplate->replaceIdentifier('date', LocaleUtil::localizeDate($oEntry->getPublishAtTimestamp()));
		$oEntryTemplate->replaceIdentifier('title', $oEntry->getTitle());
		$oEntryTemplate->replaceIdentifier('comment_count', $oEntry->countJournalComments($oCommentQuery));

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
		if($oEntryTemplate->hasIdentifier('journal_comments')) {
			$oEntryTemplate->replaceIdentifier('journal_comments', $this->renderComments($oEntry->getJournalComments($oCommentQuery), $oEntry, $oEntry === $this->oEntry));
		}
		if($oEntryTemplate->hasIdentifier('journal_gallery') && $oEntry->countJournalEntryImages() > 0) {
			$oEntryTemplate->replaceIdentifier('journal_gallery', $this->renderGallery($oEntry));
		}
		
		return $oEntryTemplate;
	}

	private function renderComments($aComments, JournalEntry $oEntry = null, $bIsDetailView = false) {
		$oEntryTemplate = $this->constructTemplate('comments');
		$iCountComments = count($aComments);

		// don't display "no comments" in journal entry detail because it is obvious and only disturbing
		if(!$bIsDetailView || $iCountComments > 0) {
			$oEntryTemplate->replaceIdentifier('comment_count_info', StringPeer::getString('journal.comment_count', null, null, array('comment_count' => $iCountComments)));
		}
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
		if(!$oEntry->commentsEnabled()) {
			return null;
		}

		//Disabled comments at this point means moderated
		$oLeaveCommentTemplate = $this->constructTemplate(!$oEntry->getJournal()->getEnableComments() ? 'leave_comment_moderated' : 'leave_comment');

		// Display catcha if enabled and user is not authenticated
		if($oEntry->getJournal()->getUseCaptcha() && !Session::getSession()->isAuthenticated()) {
			$oLeaveCommentTemplate->replaceIdentifier('captcha', FormFrontendModule::getRecaptchaCode('journal_comment'));
		} elseif(!Manager::isPost()) {
			if($oUser = Session::user()) {
				$_REQUEST['comment_name'] = $oUser->getFullName();
				$_REQUEST['comment_email'] = $oUser->getEmail();
			}
		}
		$oLeaveCommentTemplate->replaceIdentifier('is_authenticated', Session::getSession()->isAuthenticated() ? true : null);
		$oLeaveCommentTemplate->replaceIdentifier('comment_action', LinkUtil::link($oEntry->getLink($this->oPage, 'add_comment')));
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
	
	// For adding comments
	private function displayAddComment($oTemplate) {
		if($this->oEntry === null) {
			return $this->displayEntry($oTemplate);
		}
		if(!$this->oEntry->commentsEnabled()) {
			LinkUtil::redirect(LinkUtil::link($this->oEntry->getLink()));
		}
		if(Manager::isPost() && isset($_POST['preview'])) {
			$oComment = $_POST['preview'];
			$oTemplate->replaceIdentifier('container', $this->renderComments(array($oComment), $this->oEntry), $this->sContainer);
			return;
		}
		$oTemplate->replaceIdentifier('container', $this->renderAddComment($this->oEntry), $this->sContainer);
	}

	private function fillAuxilliaryContainers(Template $oTemplate) {
		if($this->sAuxiliaryContainer && $oTemplate->hasIdentifier('container', $this->sAuxiliaryContainer)) {
			foreach($this->aWidgets as $sWidget) {
				$sMethodName = "render".StringUtil::camelize($sWidget, true)."Widget";
				$oTemplate->replaceIdentifierMultiple('container', $this->$sMethodName(), $this->sAuxiliaryContainer);
			}
		}
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
		$oQuery = FrontendJournalEntryQuery::create();
		$oQuery->filterByDate($this->iYear, $this->iMonth, $this->iDay);
		$sMethodName = StringUtil::camelize("display_overview_$this->sOverviewMode");
		$this->$sMethodName($oTemplate, $oQuery);
	}
	
	private function displayEntry($oTemplate) {
		if($this->oEntry === null) {
			LinkUtil::redirect(LinkUtil::link($this->oPage->getLinkArray(), 'FrontendManager'));
		}
		$oEntryTemplate = $this->constructTemplate('full_entry');
		if($aLink = Session::getSession()->getAttribute(self::SESSION_LAST_OVERVIEW_ITEM_LINK)) {
			$sOverviewHref = LinkUtil::link($aLink);
		} else {
			$sOverviewHref = LinkUtil::link($this->oPage->getLink());
		}
		$oEntryTemplate->replaceIdentifier('return_to_list_view', TagWriter::quickTag('a', array('class'=> 'back_to_overview', 'href' => $sOverviewHref, 'title' => StringPeer::getString('journal.back_to_list_view')), StringPeer::getString('journal.back_to_list_view')));
		$oTemplate->replaceIdentifier('container', $this->renderEntry($this->oEntry, $oEntryTemplate), $this->sContainer);
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
	* renderRssFeedWidget()
	* 
	* description: display rss feed link
	* @return Template object
	*/	
	private function renderRssFeedWidget() {
		$oTemplate = $this->constructTemplate('widget_rss_feed');
		$oTemplate->replaceIdentifier('journal_feed_link', LinkUtil::link($this->oPage->getFullPathArray()));
		if($sTitle = StringPeer::getString('journal.feed_title}}')) {
			$oTemplate->replaceIdentifier('journal_feed_link_title', ' title="'.$sTitle.'"', null, Template::NO_HTML_ESCAPE);
		}
		return $oTemplate;
	}

 /**
	* renderJournalsWidget()
	* 
	* description: renders a journals list with links, like the tag cloud, but fixed categories
	* @return Template object
	*/	
	private function renderJournalsWidget() {
		if(!is_array($this->aJournalIds) || count($this->aJournalIds) < 2) {
			return;
		}
		$oTemplate = $this->constructTemplate('widget_journals');
		if($this->journalFilterIsActive()) {
			$sHref = LinkUtil::link($this->oNavigationItem->getLink(), null, array(self::RESET_JOURNALS => 'true'));
			$oTemplate->replaceIdentifier('activate_journals_href', $sHref, null, Template::NO_HTML_ESCAPE);
		}
		foreach(JournalQuery::create()->findPks($this->aJournalIds) as $oJournal) {
			if(is_array($this->aFilteredJournalIds) && in_array($oJournal->getId(), $this->aFilteredJournalIds)) {
				$oLink = TagWriter::quickTag('a', array('class' => 'journal_item active', 'title' => StringPeer::getString('journal_id_link_title.remove'), 'href' => LinkUtil::link($this->oNavigationItem->getLink(), null, array(self::REMOVE_JOURNAL => $oJournal->getId()))), $oJournal->getName());
			} else {
				$oLink = TagWriter::quickTag('a', array('class' => 'journal_item', 'title' => StringPeer::getString('journal_id_link_title.add', null, null, array('journal_name' => $oJournal->getName()), true), 'href' => LinkUtil::link($this->oNavigationItem->getLink(), null, array(self::ADD_JOURNAL => $oJournal->getId()))), $oJournal->getName());
			}
			$oTemplate->replaceIdentifierMultiple('journal_link', $oLink);
		}
		return $oTemplate;
	}

 /**
	* renderTagCloudWidget()
	* 
	* description: 
	* display tags with variable font-size, @see config.yml section journal tag_cloud [pixel_size_min, pixel_size_max]
	* @return Template object / null
	*/	
	private function renderTagCloudWidget() {
		// get all tags related to
		// • model JournalEntry
		// • active journal_enties with current journal_id 
		$aIncludeJournalEntryIds = FrontendJournalEntryQuery::create()->filterByJournalId($this->aJournalIds)->select('Id')->find()->getData();
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
		$oTemplate = $this->constructTemplate('widget_tag');
		if($this->tagFilterIsActive()) {
			$sHref = LinkUtil::link($this->oNavigationItem->getLink(), null, array(self::RESET_TAGS => 'true'));
			$oTemplate->replaceIdentifier('reset_tags_href', $sHref, null, Template::NO_HTML_ESCAPE);
		}
		
		$oItemPrototype = $this->constructTemplate('widget_tag_item');
		$sLabelEntry = StringPeer::getString('wns.');
		foreach($aTags as $sName => $iCount) {
			$oItemTemplate = clone $oItemPrototype;
			if($bUseSizes) {
				$iFontSize = (int) ceil($iMinPixelFontSize + (($iCount - $iMinCount) * $iPixelStep));
				$oItemTemplate->replaceIdentifier('size_style', ' style="font-size:'.$iFontSize.'px;line-height:'.ceil($iFontSize * 1.2).'px;"', null, Template::NO_HTML_ESCAPE);
			}
			if(is_array($this->aFilteredTags) && in_array($sName, $this->aFilteredTags)) {
				$oItemTemplate->replaceIdentifier('class_active', ' active');
				$oItemTemplate->replaceIdentifier('tag_link_title', StringPeer::getString('tag_link_title.remove'));
				$oItemTemplate->replaceIdentifier('tag_link', LinkUtil::link($this->oNavigationItem->getLink(), null, array(self::REMOVE_TAG => $sName)));
			} else {
				$oItemTemplate->replaceIdentifier('tag_link', LinkUtil::link($this->oNavigationItem->getLink(), null, array(self::ADD_TAG => $sName)));
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
		$aResult = FrontendJournalEntryQuery::create()->filterByJournalId($this->aJournalIds)->findDistinctDates();
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

 /**
	* renderDateNavigationWidget()
	* 
	* description: display date tree configured in journal/config/config.yml
	* @return Template object
	*/
	private function renderDateNavigationWidget() {
		$oTemplate = $this->constructTemplate('widget_date_navigation');
		if($this->archiveIsActive()) {
			$sHref = LinkUtil::link($this->oPage->getLinkArray());
			$oTemplate->replaceIdentifier('overview_page_href', $sHref, null, Template::NO_HTML_ESCAPE);
		}
		$oNavigation = new Navigation('journal_date_navigation_widget');
		$oTemplate->replaceIdentifier('date_navigation', $oNavigation->parse(PageNavigationItem::navigationItemForPage($this->oPage)));
		return $oTemplate;
	}
	
 /**
	* renderRecentEntriesWidget()
	* 
	* description: renders a journal entry list
	* change limit count by overwriting the config param "recent_entry_widget_limit" in your site/config/config.yml
	* @return Template object
	*/	
	private function renderRecentEntriesWidget() {
		$oTemplate = $this->constructTemplate('widget_recent_entries');
		$oItemPrototype = $this->constructTemplate('widget_recent_entry_item');
		$iLimit = Settings::getSetting('journal', 'recent_entries_widget_limit', 7);
		$oQuery	= FrontendJournalEntryQuery::create()->mostRecentFirst()->limit($iLimit)->filterByJournalId($this->aJournalIds);
		foreach($oQuery->find() as $oEntry) {
			$oTemplate->replaceIdentifierMultiple('entries', $this->renderEntry($oEntry, clone $oItemPrototype));
		}
		return $oTemplate;
	}
	
 /**
	* renderRecentCommentsWidget()
	* 
	* description: renders a comments teaser list
	* change limit count by overwriting the config param "recent_comments_limit" in your site/config/config.yml
	* @return Template object
	*/	
	private function renderRecentCommentsWidget() {
		$oTemplate = $this->constructTemplate('widget_recent_comments');
		$oItemPrototype = $this->constructTemplate('widget_recent_comment_item');
		$iLimit = Settings::getSetting('journal', 'recent_comments_limit', 3);
		$oQuery	= JournalCommentQuery::create()->excludeUnverified()->mostRecentFirst()->limit($iLimit)->useJournalEntryQuery()->filterByJournalId($this->aJournalIds)->endUse()->groupByJournalEntryId();
		foreach($oQuery->find() as $oComment) {
			$oCommentTemplate = clone $oItemPrototype;
			if($oEntry = $oComment->getJournalEntry()) {
				$oCommentTemplate->replaceIdentifier('title', $oEntry->getTitle());
				$oDetailLink = TagWriter::quickTag('a', array('class' => 'read_more', 'href' => LinkUtil::link($oEntry->getLink($this->oPage)).'#comments'), StringPeer::getString('journal_entry_teaser.read_more'));
				$oCommentTemplate->replaceIdentifier('more_link', $oDetailLink);
			}
			$oCommentTemplate->replaceIdentifier('name', $oComment->getUsername());
			$oCommentTemplate->replaceIdentifier('date', $oComment->getCreatedAtLocalized());
			$oCommentTemplate->replaceIdentifier('text_stripped', StringUtil::truncate(strip_tags($oComment->getText()), 45));
			$oCommentTemplate->replaceIdentifier('text', $oComment->getText());
			$oTemplate->replaceIdentifierMultiple('comments', $oCommentTemplate);
		}
		return $oTemplate;
	}

 /**
	* renderCollapsibleDateTreeWidget()
	* 
	* description: display collapsible date tree without link on year
	* include javascript file web/js/journal-collapsible-date-tree.js
	* @return Template object
	*/	
	private function renderCollapsibleDateTreeWidget() {
		$iTreeWidgetLevels = Settings::getSetting('journal', 'display_journal_collapsible_tree_levels', 2);
		
		$aResult = FrontendJournalEntryQuery::create()->filterByJournalId($this->aJournalIds)->findDistinctDates();
		
		$oTemplate = $this->constructTemplate('widget_collapsible_date_tree');
		if($this->archiveIsActive()) {
			$sHref = LinkUtil::link($this->oPage->getLinkArray());
			$oTemplate->replaceIdentifier('reset_archive_href', $sHref, null, Template::NO_HTML_ESCAPE);
		}

		$oItemPrototype = $this->constructTemplate('widget_collapsible_date_tree_item');
		
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
		$cOutput = function($aDate, $sFormat, $bIsActive) use (&$aStack, $oItemPrototype, $oPage) {
			$oTemplate = clone $oItemPrototype;
			array_push($aStack, $oTemplate);
			foreach($aDate as $sPeriod => $sValue) {
				$oTemplate->replaceIdentifier(strtolower($sPeriod), $sValue);
			}
			$oDate = new DateTime();
			$oDate->setDate($aDate['Year'], @$aDate['Month'] ? $aDate['Month'] : 1, @$aDate['Day'] ? $aDate['Day'] : 1);
			$oTemplate->replaceIdentifier('full_name', LocaleUtil::localizeDate($oDate, null, $sFormat));
			$aKeys = array_keys($aDate);
			$oTemplate->replaceIdentifier('name', $aDate[$aKeys[count($aKeys)-1]]);
			$oTemplate->replaceIdentifier('level', count($aKeys));
			$oTemplate->replaceIdentifier('link', LinkUtil::link($oPage->getFullPathArray(array_values($aDate))));
			if($bIsActive) {
				$oTemplate->replaceIdentifier('class_is_active', 'is_active');
			}
		};
		
		foreach($aResult as $aDate) {
			$oCurrentTemplate = null;
			
			// Make year template whenever the year changes and add it to main template
			if($aDate['Year'] !== $sPreviousYear) {
				$cReduceToLevel(1);
				$sPreviousYear = $aDate['Year'];
				
				$cOutput(array('Year' => $aDate['Year']), 'Y', $this->iYear === $aDate['Year']);
			}
			
			// Render 2nd level months
			if($iTreeWidgetLevels === 1) continue;
			// Make month template whenever month changes (or year, because it can happen that two months are the same when a year changes) 
			// Add it to year template
			if($aDate['Year'] !== $sPreviousYear || $aDate['Month'] !== $sPreviousMonth) {
				$cReduceToLevel(2);
				$sPreviousMonth = $aDate['Month'];
				$cOutput(array('Year' => $aDate['Year'], 'Month' => $aDate['Month']), 'B', $this->iMonth === $aDate['Month']);
			}
			
			// Render 3rd level days
			if($iTreeWidgetLevels === 2) continue;
			$cReduceToLevel(3);
			$cOutput(array('Year' => $aDate['Year'], 'Month' => $aDate['Month'], 'Day' => $aDate['Day']), 'x', $this->iDay === $aDate['Day']);
		}
		
		$cReduceToLevel(1);
		
		return $oTemplate;
	}
	
 /**
	* renderBlogrollWidget()
	* 
	* description: renders a simple list of link managed in the links admin module
	* define the required link cagegory by overwriting the config param "blogroll_link_category_id" in your site/config/config.yml
	* @return Template object
	*/	
	private function renderBlogrollWidget() {
		$iLinkCategoryId = Settings::getSetting('journal', 'blogroll_link_category_id', null);
		if($iLinkCategoryId === null) {
			return null;
		}
		$aLinks = LinkQuery::create()->filterByLinkCategoryId($iLinkCategoryId)->orderBySort()->find();
		if(empty($aLinks)) {
			return null;
		}
		$oTemplate = $this->constructTemplate('widget_blogroll');
		$oLinkPrototype = $this->constructTemplate('widget_blogroll_link');
		foreach($aLinks as $oLink) {
			$oLinkTemplate = clone $oLinkPrototype;
			$oLinkTemplate->replaceIdentifier('name', $oLink->getName());
			$oLinkTemplate->replaceIdentifier('description', $oLink->getDescription());
			$oLinkTemplate->replaceIdentifier('url', $oLink->getUrl());
			$oTemplate->replaceIdentifierMultiple('link', $oLinkTemplate);
		}
		return $oTemplate;
	}
	
 /**
	* renderGallery()
	* 
	* description: display image gallery
	* @return Template object
	*/	
	private function renderGallery(JournalEntry $oEntry) {
		$oEntryTemplate = $this->constructTemplate('journal_gallery');
		$oListTemplate = new Template('helpers/gallery');
		$oListTemplate->replaceIdentifier('title', $this->oEntry->getTitle());

		foreach($this->oEntry->getImages() as $oJournalEntryImage) {
			$oDocument = $oJournalEntryImage->getDocument();
			$oItemTemplate = new Template('helpers/gallery_item');
			$oItemTemplate->replaceIdentifier('jounal_entry_id', $this->oEntry->getId());
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

	public function currentEntriesPerPage() {
		return $this->iEntriesPerPage;
	}

	public function currentJournalIds() {
		return $this->aJournalIds;
	}

	public function dateNavigationItemsVisible() {
		return $this->bDateNavigationItemsVisible;
	}

	public function listTemplateSets() {
		$aResult = array();
		foreach(ResourceFinder::create(array(DIRNAME_MODULES, self::getType(), $this->getModuleName(), DIRNAME_TEMPLATES))->addDirPath()->returnObjects()->find() as $oSet) {
			$aResult[$oSet->getFileName()] = StringPeer::getString('journal.template_set_'.$oSet->getFileName(), null, StringUtil::makeReadableName($oSet->getFileName()));
		}
		return array('options' => $aResult, 'current' => $this->sTemplateSet);
	}

	public function listContainers() {
		$aContainers = $this->oPage->getTemplate()->identifiersMatching("container", Template::$ANY_VALUE);
		$aResult = array();
		foreach($aContainers as $oContainer) {
			$aResult[] = $oContainer->getValue();
		}
		return array('options' => $aResult, 'current' => $this->sContainer, 'current_auxiliary' => $this->sAuxiliaryContainer);
	}
	
	public function listJournals() {
		return JournalQuery::create()->orderByName()->find()->toKeyValue('Id', 'Name');
	}
	
	public function listWidgets() {
		$aWidgetTypes = array();
		$aWidgetTypesOrdered = array();
		foreach(get_class_methods($this) as $sMethodName) {
			if(StringUtil::startsWith($sMethodName, 'render') && StringUtil::endsWith($sMethodName, 'Widget')) {
				$oWidget = new StdClass();
				$oWidget->name = StringUtil::deCamelize(substr($sMethodName, strlen('render'), -strlen('Widget')));
				$oWidget->current = in_array($oWidget->name, $this->aWidgets, true);
				$sStringKey = 'journal_config.'.$oWidget->name;
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
		ksort($aWidgetTypesOrdered);
		$aWidgetTypes = array_merge($aWidgetTypesOrdered, $aWidgetTypes);
		return $aWidgetTypes;
	}

	private $oJournalEntryList = null;
	public function entryList() {
		$this->oJournalEntryList = new JournalEntryListWidgetModule();
		$this->oJournalEntryList->getDelegate()->setJournalId($this->aJournalIds);
		
		$oIncluder = new ResourceIncluder();
		JournalEntryListWidgetModule::includeResources($oIncluder);

		return $oIncluder->getIncludes()->render().$this->oJournalEntryList->doWidget()->render();
	}

	public function setCurrentJournal($aJournalIds) {
		if($this->oJournalEntryList) {
			$this->oJournalEntryList->getDelegate()->setJournalId($aJournalIds);
		}
	}
	
	private function validate($aData) {
		$oFlash = Flash::getFlash();
		$oFlash->setArrayToCheck($aData);
		if(!isset($aData['journal_ids'])) {
			$oFlash->addMessage('journal_ids_required');
		}
		$oFlash->finishReporting();
	}
	
	public function saveJournalPageConfiguration($aData) {
		$this->validate($aData);
		if(!Flash::noErrors()) {
			throw new ValidationException();
		}
		if($this->oJournalEntryList) {
			$this->oJournalEntryList->getDelegate()->setJournalId($aData['journal_ids']);
		}
		
		$this->oPage->updatePageProperty('journal:overview_action', $aData['mode']);
		$this->oPage->updatePageProperty('journal:journal_id', implode(',', array_filter($aData['journal_ids'])));
		// reset journal filter because a journal id that is not configured anymore might be in the session and take effect
		Session::getSession()->resetAttribute(self::SESSION_JOURNAL_FILTER);
		$this->oPage->updatePageProperty('journal:entries_per_page', $aData['entries_per_page'] == '' ? null : $aData['entries_per_page']);
		$this->oPage->updatePageProperty('journal:template_set', $aData['template_set']);
		$this->oPage->updatePageProperty('journal:container', $aData['container']);
		$this->oPage->updatePageProperty('journal:auxiliary_container', $aData['auxiliary_container']);
		$this->oPage->updatePageProperty('journal:date_navigation_items_visible', $aData['date_navigation_items_visible'] === '1' ? 1 : 0);
		
		$aWidgets =  array();
		foreach($aData['widgets'] as $sWidgetName) {
			if($sWidgetName !== false) {
				$aWidgets[] = $sWidgetName;
			}
		}
		$this->oPage->updatePageProperty('journal:widgets', implode(',', $aWidgets));
		$this->updateFlagsFromProperties();
	}
}
