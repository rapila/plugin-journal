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
	

	public function onNavigationItemChildrenRequested(NavigationItem $oNavigationItem) {
		$mIdentifier = $oNavigationItem->getIdentifier();
		if($oNavigationItem instanceof PageNavigationItem && $oNavigationItem->getMe()->isOfType('journal')) {
			

		} else if($oNavigationItem instanceof VirtualNavigationItem) {
			
		}
	}
}
