<?php
class JournalFrontendModule extends DynamicFrontendModule {
	private static $DISPLAY_OPTIONS = array('recent_journal_entry_teaser', 'recent_comment_teaser', 'recent_journal_entry');

	private $bIsJournalEntry = true;
	private $oJournalPage = null;

	const DISPLAY_MODE = 'display_mode';
	const PAGE_SEPARATOR = '-';

	public function renderFrontend() {
		$aOptions = @unserialize($this->getData());
		$sDisplayMode = $aOptions[self::DISPLAY_MODE];
		$aJournalParams = isset($aOptions['journal']) ? explode('-', $aOptions['journal']) : array();
		// get journal ids
		if(isset($aJournalParams[1])) {
			$this->oJournalPage = PageQuery::create()->active()->findPk($aJournalParams[1]);
			$mJournalId = explode(',', $this->oJournalPage->getPagePropertyValue('journal:journal_id', ''));

		} else {
			$mJournalId = $aJournalParams[0];
		}

		switch($sDisplayMode) {
			case('recent_journal_entry_teaser') : return $this->renderRecentJournalEntryTeaser($mJournalId);
			case('recent_comment_teaser') : return $this->renderRecentJournalCommentTeaser($mJournalId);
			case('recent_journal_entry') : return $this->renderRecentJournalEntry($mJournalId);
			default: return null;
		}
	}

	private function renderRecentJournalEntryTeaser($mJournalId) {
		$oJournalEntry = FrontendJournalEntryQuery::create()->filterByJournalId($mJournalId)->mostRecentFirst()->findOne();
		if($oJournalEntry === null) {
			return null;
		}
		$oTemplate = $this->constructTemplate('journal_entry_teaser');
		$sHref = LinkUtil::link($oJournalEntry->getLink($this->oJournalPage));
		$oTemplate->replaceIdentifier('title', TagWriter::quickTag('a', array('href' => $sHref), $oJournalEntry->getTitle()));
		$oTemplate->replaceIdentifier('title_without_link', $oJournalEntry->getTitle());
		$oTemplate->replaceIdentifier('link_to_detail', $sHref);
		// publish_at and date are deprecated as their usage varies between the template types
		$oTemplate->replaceIdentifier('publish_at', $oJournalEntry->getPublishAt('U'));
		$oTemplate->replaceIdentifier('date', LocaleUtil::localizeDate($oJournalEntry->getPublishAtTimestamp()));
		$oTemplate->replaceIdentifier('date_timestamp', $oJournalEntry->getPublishAtTimestamp());
		$oTemplate->replaceIdentifier('user_name', $oJournalEntry->getUserRelatedByCreatedBy()->getFullName());
		$sTextShort = RichtextUtil::parseStorageForFrontendOutput($oJournalEntry->getTextShort());
		$oTemplate->replaceIdentifier('text_short', $sTextShort);
		$oTemplate->replaceIdentifier('text_short_truncated', StringUtil::truncate(strip_tags($sTextShort), 300));
		return $oTemplate;
	}

	private function renderRecentJournalEntry($mJournalId) {
		$oJournalEntry = FrontendJournalEntryQuery::create()->filterByJournalId($mJournalId)->mostRecentFirst()->findOne();
		if($oJournalEntry === null) {
			return null;
		}
		$oTemplate = $this->constructTemplate('journal_entry');
		$sHref = LinkUtil::link($oJournalEntry->getLink($this->oJournalPage));
		$oTemplate->replaceIdentifier('title', TagWriter::quickTag('a', array('href' => $sHref), $oJournalEntry->getTitle()));
		$oTemplate->replaceIdentifier('link_to_detail', $sHref);
		// publish_at and date are deprecated as their usage varies between the template types
		$oTemplate->replaceIdentifier('publish_at', $oJournalEntry->getPublishAt(' %e. %B %Y'));
		$oTemplate->replaceIdentifier('date', $oJournalEntry->getPublishAtTimestamp());
		$oTemplate->replaceIdentifier('date_timestamp', $oJournalEntry->getPublishAtTimestamp());
		$oTemplate->replaceIdentifier('user_name', $oJournalEntry->getUserRelatedByCreatedBy()->getFullName());
		$sText = RichtextUtil::parseStorageForFrontendOutput($oJournalEntry->getText());
		$oTemplate->replaceIdentifier('text', $sText);
		if(!$oTemplate->hasIdentifier('images')) {
			return $oTemplate;
		}
		$iImages = $oJournalEntry->getImages();
		if(count($iImages) === 0) {
			return $oTemplate;
		}
		// images
		$oGalleryTemplate = new Template('helpers/gallery');
		$oItemPrototype = new Template('helpers/gallery_item');
		foreach($iImages as $iIndex => $oJournalEntryImage) {
			$oDocument = $oJournalEntryImage->getDocument();
			$oItemTemplate = clone $oItemPrototype;
			$oItemTemplate->replaceIdentifier('jounal_entry_id', $oJournalEntry->getId());
			$oItemTemplate->replaceIdentifier('index', $iIndex);
			$oDocument->renderListItem($oItemTemplate);
			$oGalleryTemplate->replaceIdentifierMultiple('items', $oItemTemplate);
		}
		$oTemplate->replaceIdentifierMultiple('images', $oGalleryTemplate);
		return $oTemplate;
	}

	private function renderRecentJournalCommentTeaser($mJournalId) {
		$oJournalComment = JournalCommentQuery::create()->excludeUnverified()->joinJournalEntry()->useQuery('JournalEntry')->filterByJournalId($mJournalId)->excludeDraft()->endUse()->mostRecentFirst()->findOne();

		if($oJournalComment === null) {
			return null;
		}
		$oTemplate = $this->constructTemplate('journal_comment_teaser');
		$sHref = LinkUtil::link($oJournalComment->getJournalEntry()->getLink($this->oJournalPage)).'#comments';
		$oTemplate->replaceIdentifier('title', TagWriter::quickTag('a', array('rel' => 'internal', 'href' => $sHref), $oJournalComment->getJournalEntry()->getTitle()));
		$oTemplate->replaceIdentifier('link_to_detail', $sHref);
		$oTemplate->replaceIdentifier('created_at', $oJournalComment->getCreatedAt('U'));
		$oTemplate->replaceIdentifier('name', $oJournalComment->getUsername());
		$oTemplate->replaceIdentifier('text', $oJournalComment->getText(), null, Template::NO_HTML_ESCAPE);
		return $oTemplate;
	}

	public function renderBackend() {
		$oTemplate = $this->constructTemplate('config');
		// Display options
		$aDisplayOptions = array();
		foreach(self::$DISPLAY_OPTIONS as $sDisplayMode) {
			$aDisplayOptions[$sDisplayMode] = TranslationPeer::getString('journal.display_mode.'.$sDisplayMode, null, StringUtil::makeReadableName($sDisplayMode));
		}
		$oTemplate->replaceIdentifier('display_options', TagWriter::optionsFromArray($aDisplayOptions, null, null, array()));

		// Journal pages and journal options
		$aJournalOptions = array();
		foreach(PageQuery::create()->filterByPageType('journal')->orderByName()->find() as $oPage) {
			$aJournalOptions['page'.self::PAGE_SEPARATOR . $oPage->getId()] = TranslationPeer::getString('wns.journal.journal_page_name', null, null, array('name' => $oPage->getLinkText()));
		}
		foreach(JournalQuery::create()->orderByName()->find() as $oJournal) {
			$aJournalOptions[$oJournal->getId()] = TranslationPeer::getString('wns.journal.journal_name', null, null, array('name' => $oJournal->getName()));
		}
		$oTemplate->replaceIdentifier('journal_options', TagWriter::optionsFromArray($aJournalOptions));
		return $oTemplate;
	}
}
