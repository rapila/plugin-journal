<?php

require_once('htmlpurifier/HTMLPurifier.standalone.php');

class JournalFilterModule extends FilterModule {

	/**
	* Allow legacy paths (/blog/entry/year-month-day-slug) and redirect to new path (/blog/year/month/day/slug)
	*/
	public function onPageNotFoundDetectionComplete($bIsNotFound, $oPage, $oNavigationItem, $aContainer) {
		if(!$bIsNotFound || !$oPage->isOfType('journal') || !isset($_REQUEST['entry'])) {
			return;
		}
		$aParams = explode('-', $_REQUEST['entry']);
		$iYear = (int)array_shift($aParams);
		$iMonth = (int)array_shift($aParams);
		$iDay = (int)array_shift($aParams);
		$sSlug = implode('-', $aParams);

		LinkUtil::redirect(LinkUtil::link(array_merge($oPage->getLinkArray(), array($iYear, $iMonth, $iDay, $sSlug))));
	}

	public function onNavigationItemChildrenRequested(NavigationItem $oNavigationItem) {
		$mIdentifier = $oNavigationItem->getIdentifier();
		if($oNavigationItem instanceof PageNavigationItem && $oNavigationItem->getMe()->isOfType('journal')) {
			$this->addChildrenToPageNavigationItem($oNavigationItem);
		}
		else if($oNavigationItem instanceof VirtualNavigationItem) {
			$this->addChildrenToVirtualNavigationItem($oNavigationItem);
		}
	}

	public function onNavigationItemChildrenCacheDetectOutdated(NavigationItem $oNavigationItem, Cache $oCache, $aIsOutdated) {
		if($oNavigationItem instanceof PageNavigationItem && !$oNavigationItem->getMe()->isOfType('journal')) {
			return;
		}
		if(!StringUtil::startsWith($oNavigationItem->getType(), 'journal-')) {
			return;
		}
		if($aIsOutdated[0]) {
			return;
		}
		if(Manager::getCurrentPrefix() === 'preview') {
			return;
		}
		$aIsOutdated[0] = $oCache->isOlderThan(FrontendJournalEntryQuery::create());
	}

	private function addChildrenToPageNavigationItem(NavigationItem $oNavigationItem) {
		// Append virtual navigation items for year, overview and feed
		$sJournalId = $oNavigationItem->getMe()->getPagePropertyValue('journal:journal_id', null);
		$aJournalIds = explode(',',$sJournalId);
		$bDateNavigationItemsVisible = (bool) $oNavigationItem->getMe()->getPagePropertyValue('journal:date_navigation_items_visible', null);
		$sDateNavigationItemClass = $bDateNavigationItemsVisible ? 'VirtualNavigationItem' : 'HiddenVirtualNavigationItem';

		// Feed item
		$oFeedItem = new HiddenVirtualNavigationItem('journal-feed', 'feed', TranslationPeer::getString('wns.journal.feed', null, 'feed'), null, $aJournalIds);
		$oFeedItem->bIsIndexed = false; //Don’t index the feed item or else you’ll be exit()-ed before finishing the index
		$oNavigationItem->addChild($oFeedItem);

		// Overview list
		$bOverviewIsList = $oNavigationItem->getMe()->getPagePropertyValue('journal:overview_action', 'list') === 'list';
		if(!$bOverviewIsList && Settings::getSetting('journal', 'overview_list_link_auto_display', null)) {
			$oOverviewItem = new VirtualNavigationItem('journal-overview_list', 'list', TranslationPeer::getString('wns.journal.list'), null, $aJournalIds);
			$oOverviewItem->bIsIndexed = false;
			$oNavigationItem->addChild($oOverviewItem);
		}

		// Year items
		foreach(FrontendJournalEntryQuery::create()->findAvailableYearsByJournalId($aJournalIds) as $iYear) {
			$oItem = new $sDateNavigationItemClass('journal-year', $iYear, TranslationPeer::getString('wns.journal.year', null, $iYear, array('year' => $iYear)), null, array($aJournalIds, $iYear));
			$oItem->bIsIndexed = false;
			$oNavigationItem->addChild($oItem);
		}
	}

	private function addChildrenToVirtualNavigationItem($oNavigationItem) {
		$aData = $oNavigationItem->getData();
		$sDateNavigationItemClass = get_class($oNavigationItem);

		// Append virtual navigation items for months, days, journal entries and comment
		// Months
		if($oNavigationItem->getType() === 'journal-year') {
			list($aJournalIds, $iYear) = $aData;
			foreach(FrontendJournalEntryQuery::create()->findAvailableMonthsByJournalId($aJournalIds, $iYear) as $iMonth) {
				$oItem = new $sDateNavigationItemClass('journal-month', $iMonth, TranslationPeer::getString('wns.journal.month', null, $iMonth, array('year' => $iYear, 'month' => $iMonth)), null, array($aJournalIds, $iYear, $iMonth));
				$oItem->bIsIndexed = false;
				$oNavigationItem->addChild($oItem);
			}
		} else if($oNavigationItem->getType() === 'journal-month') {

			// Days
			list($aJournalIds, $iYear, $iMonth) = $aData;
			foreach(FrontendJournalEntryQuery::create()->findAvailableDaysByJournalId($aJournalIds, $iYear, $iMonth) as $iDay) {
				$oItem = new $sDateNavigationItemClass('journal-day', $iDay, TranslationPeer::getString('wns.journal.day', null, $iDay, array('year' => $iYear, 'month' => $iMonth, 'day' => $iDay)), null, array($aJournalIds, $iYear, $iMonth, $iDay));
				$oItem->bIsIndexed = false;
				$oNavigationItem->addChild($oItem);
			}
		} else if($oNavigationItem->getType() === 'journal-day') {

			// Journal entries
			list($aJournalIds, $iYear, $iMonth, $iDay) = $aData;
			foreach(FrontendJournalEntryQuery::create()->filterByDate($iYear, $iMonth, $iDay)->filterByJournalId($aJournalIds)->find() as $oEntry) {
				$oItem = new VirtualNavigationItem('journal-entry', $oEntry->getSlug(), $oEntry->getTitle(), null, $oEntry);
				$oNavigationItem->addChild($oItem);
			}
		} else if($oNavigationItem->getType() === 'journal-entry') {

			// Comments
			$oAddCommentItem = new HiddenVirtualNavigationItem('journal-add_comment', 'add_comment', TranslationPeer::getString('journal.comment.add'), null, $oNavigationItem->getData());
			$oAddCommentItem->bIsIndexed = false;
			$oNavigationItem->addChild($oAddCommentItem);
		}
	}

	public function onPageHasBeenSet($oPage, $bIsNotFound, $oNavigationItem) {
		if($bIsNotFound || !$oPage->isOfType('journal')) {
			return;
		}
		// If is feed then render feed
		if($oNavigationItem instanceof VirtualNavigationItem && $oNavigationItem->getType() === 'journal-feed') {
			$oFeed = new JournalFileModule(false, $oPage, $oNavigationItem->getData());
			$oFeed->renderFile();exit;
		}

		// Add-Comment handling/validation
		else if($oNavigationItem instanceof VirtualNavigationItem && $oNavigationItem->getType() === 'journal-add_comment' && Manager::isPost()) {
			$oEntry = $oNavigationItem->getData();
			if(!$oEntry->commentsEnabled()) {
				LinkUtil::redirect(LinkUtil::link($oEntry->getLink()));
			}
			$this->handleNewJournalComment($oPage, $oEntry);
		}
		//Add the feed include
		ResourceIncluder::defaultIncluder()->addCustomResource(array('template' => 'feed', 'location' => LinkUtil::link($oPage->getLinkArray('feed'))));
	}

	private function handleNewJournalComment($oPage, $oEntry) {
		$oFlash = Flash::getFlash();

		// Validate form and create new comment and
		$oComment = new JournalComment();
		$oComment->setUsername($_POST['comment_name']);
		$oFlash->checkForValue('comment_name', 'comment_name_required');
		$oComment->setEmail($_POST['comment_email']);
		$oFlash->checkForEmail('comment_email', 'comment_email_required');
		if($oEntry->getJournal()->getUseCaptcha() && !Session::getSession()->isAuthenticated()
				&& !FormFrontendModule::validateRecaptchaInput() && !isset($_POST['preview'])) {
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
			$_POST['preview'] = $oComment;
		} else if(Flash::noErrors()) {
			$oEntry->addJournalComment($oComment);

			// Post is considered as spam
			$bIsProblablySpam = isset($_POST['important_note']) && $_POST['important_note'] != null;
			$sCommentNotificationTemplate = 'e_mail_comment_notified';

			// Prevent publication if comments are not enabled or post is spam
			if(!$oEntry->getJournal()->getEnableComments() || $bIsProblablySpam) {
				if(!Session::getSession()->isAuthenticated()) {
					$oComment->setIsPublished(false);
					$sCommentNotificationTemplate = 'e_mail_comment_moderated';
				}
			}
			$oComment->save();

			// Notify new comment
			if($oEntry->getJournal()->getNotifyComments()) {
				$oEmailContent = JournalPageTypeModule::templateConstruct($sCommentNotificationTemplate, $oPage->getPagePropertyValue('journal:template_set', 'default'));
				$oEmailContent->replaceIdentifier('email', $oComment->getEmail());
				$oEmailContent->replaceIdentifier('user', $oComment->getUsername());
				if($bIsProblablySpam) {
					$oEmailContent->replaceIdentifier('this_comment_is_spam_note', TranslationPeer::getString('journal.this_comment_is_spam_note', null, null, array('important_note_content' => $_POST['important_note'])));
				}
				$oEmailContent->replaceIdentifier('comment', $oComment->getText());
				$oEmailContent->replaceIdentifier('entry', $oEntry->getTitle());
				$oEmailContent->replaceIdentifier('journal', $oEntry->getJournal()->getName());
				$oEmailContent->replaceIdentifier('entry_link', LinkUtil::absoluteLink(LinkUtil::link($oEntry->getLink($oPage))));
				$oEmailContent->replaceIdentifier('deactivation_link', LinkUtil::absoluteLink(LinkUtil::link(array('journal_comment_moderation', $oComment->getActivationHash(), 'deactivate'), 'FileManager'), null, LinkUtil::isSSL()));
				$oEmailContent->replaceIdentifier('activation_link', LinkUtil::absoluteLink(LinkUtil::link(array('journal_comment_moderation', $oComment->getActivationHash(), 'activate'), 'FileManager'), null, LinkUtil::isSSL()));
				$oEmailContent->replaceIdentifier('deletion_link', LinkUtil::absoluteLink(LinkUtil::link(array('journal_comment_moderation', $oComment->getActivationHash(), 'delete'), 'FileManager'), null, LinkUtil::isSSL()));
				$sSubject = TranslationPeer::getString('journal.notification_subject', null, null, array('entry' => $oEntry->getTitle()));
				$oEmail = new EMail($sSubject, $oEmailContent);
				$oSender = $oEntry->getUserRelatedByCreatedBy();
				$oEmail->addRecipient($oSender->getEmail(), $oSender->getFullName());
				$oEmail->send();
			}
			$oSession = Session::getSession();
			Flash::getFlash()->unfinishReporting()->addMessage('journal.has_new_comment', array(), "journal_entry.new_comment_thank_you".($oEntry->getJournal()->getEnableComments() || $oSession->isAuthenticated() ? '' : '.moderated'), 'new_comment_thank_you_message', 'p')->stick();
			LinkUtil::redirect(LinkUtil::link($oEntry->getLink($oPage))."#comments");
		}
	}
}
