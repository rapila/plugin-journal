<?php

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

	public function onPageHasBeenSet($oPage, $bIsNotFound, $oNavigationItem) {
		if($bIsNotFound || !$oPage->isOfType('journal')) {
			return;
		}
		if($oNavigationItem instanceof VirtualNavigationItem && $oNavigationItem->getType() === 'journal-feed') {
			$oFeed = new JournalFileModule(false, $oPage, $oNavigationItem->getData());
			$oFeed->renderFile();exit;
		}
		//Add the feed include
		ResourceIncluder::defaultIncluder()->addCustomResource(array('template' => 'feed', 'location' => LinkUtil::link($oPage->getLinkArray('feed'))));
	}

	public function onNavigationItemChildrenRequested(NavigationItem $oNavigationItem) {
		$mIdentifier = $oNavigationItem->getIdentifier();
		if($oNavigationItem instanceof PageNavigationItem && $oNavigationItem->getMe()->isOfType('journal')) {
			//Append virtual navigation items for year, overview and feed
			$oJournal = JournalQuery::create()->findPk($oNavigationItem->getMe()->getPagePropertyValue('journal_id', null));
			$bDatesHidden = !!$oNavigationItem->getMe()->getPagePropertyValue('blog_dates_hidden', null);
			$sDateNavigationItemClass = $bDatesHidden ? 'HiddenVirtualFolderNavigationItem' : 'FolderVirtualNavigationItem';
			$iJournalId = $oJournal->getId();
			//feed
			$oFeedItem = new HiddenVirtualNavigationItem('journal-feed', 'feed', StringPeer::getString('wns.journal.feed', null, 'feed'), null, $iJournalId);
			$oNavigationItem->addChild($oFeedItem);
			//overview
			$oOverviewItem = new VirtualNavigationItem('journal-list', 'list', StringPeer::getString('wns.journal.list'), null, $iJournalId);
			$oNavigationItem->addChild($oOverviewItem);
			//year
			foreach($oJournal->possibleYears() as $iYear) {
				$oItem = new $sDateNavigationItemClass('journal-year', $iYear, StringPeer::getString('wns.journal.year', null, $iYear, array('year' => $iYear)), null, array($iJournalId, $iYear));
				$oNavigationItem->addChild($oItem);
			}
		} else if($oNavigationItem instanceof VirtualNavigationItem) {
			$aData = $oNavigationItem->getData();
			$sDateNavigationItemClass = get_class($oNavigationItem);
			if($oNavigationItem->getType() === 'journal-year') {
				list($iJournalId, $iYear) = $aData;
				$oJournal = JournalQuery::create()->findPk($iJournalId);
				foreach($oJournal->possibleMonths($iYear) as $iMonth) {
					$oItem = new $sDateNavigationItemClass('journal-month', $iMonth, StringPeer::getString('wns.journal.month', null, $iMonth, array('year' => $iYear, 'month' => $iMonth)), null, array($iJournalId, $iYear, $iMonth));
					$oNavigationItem->addChild($oItem);
				}
			} else if($oNavigationItem->getType() === 'journal-month') {
				list($iJournalId, $iYear, $iMonth) = $aData;
				$oJournal = JournalQuery::create()->findPk($iJournalId);
				foreach($oJournal->possibleDays($iYear, $iMonth) as $iDay) {
					$oItem = new $sDateNavigationItemClass('journal-day', $iDay, StringPeer::getString('wns.journal.day', null, $iDay, array('year' => $iYear, 'month' => $iMonth, 'day' => $iDay)), null, array($iJournalId, $iYear, $iMonth, $iDay));
					$oNavigationItem->addChild($oItem);
				}
			} else if($oNavigationItem->getType() === 'journal-day') {
				list($iJournalId, $iYear, $iMonth, $iDay) = $aData;
				foreach(JournalEntryQuery::create()->filterByDate($iYear, $iMonth, $iDay)->excludeDraft()->filterByJournalId($iJournalId)->find() as $oEntry) {
					$oItem = new VirtualNavigationItem('journal-entry', $oEntry->getSlug(), $oEntry->getTitle(), null, $oEntry);
					$oNavigationItem->addChild($oItem);
				}
			} else if($oNavigationItem->getType() === 'journal-entry') {
				//comment
				$oNavigationItem->addChild(new HiddenVirtualNavigationItem('journal-add_comment', 'add_comment', StringPeer::getString('journal.comment.add'), null, $oNavigationItem->getData()));
			}
		}
	}
}
