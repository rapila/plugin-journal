<?php

require_once 'model/om/BaseJournalEntry.php';

/**
 * @package model
 * @subpackage rapila-plugin-journal
 */
class JournalEntry extends BaseJournalEntry {

	public function getPublishAtLocalized($sFormat = ' %e. %B %Y') {
		if(Session::language() === 'en') {
			$sFormat = ' %e %B %Y';
		}
		return LocaleUtil::localizeDate($this->publish_at, null, $sFormat);
	}

	public function getPublishAtTimestamp() {
		return (int)$this->getPublishAt('U');
	}

	public function isNotShown() {
		return !$this->getIsPublished() || $this->getPublishAt() > date('c');
	}

	public function getPublishAtFormatted($sLanguageId = null, $sFormatString = 'x') {
		if($this->publish_at === null) {
			return null;
		}
		return LocaleUtil::localizeDate($this->publish_at, $sLanguageId, $sFormatString);
	}

	public function commentsEnabled() {
		return $this->getJournal()->commentsEnabled();
	}

	public function getJournalComments($oCriteria = null, PropelPDO $oConnection = null) {
		if($oCriteria === null) {
			$oCriteria = new Criteria();
		}
		$oCriteria->addAscendingOrderByColumn(JournalCommentPeer::CREATED_AT);
		$oCriteria->addAscendingOrderByColumn(JournalCommentPeer::ID);
		return parent::getJournalComments($oCriteria, $oConnection);
	}

	public function getImages() {
		return $this->getJournalEntryImages(JournalEntryImageQuery::create()->orderBySort(Criteria::ASC));
	}

	public function getRssAttributes($oJournalPage = null, $bIsForRpc = false) {
		$aResult = array();
		$aResult['title'] = $this->getTitle();
		$aJournalPageLink = $this->getLink($oJournalPage);
		$aResult['link'] = LinkUtil::absoluteLink(LinkUtil::link($aJournalPageLink, 'FrontendManager'), null, LinkUtil::isSSL());
		if($bIsForRpc) {
			$aResult['description'] = RichtextUtil::parseStorageForBackendOutput($this->getText())->render();
		} else {
			$aResult['description'] = RichtextUtil::parseStorageForFrontendOutput($this->getText())->render();
		}
		$aResult['author'] = $this->getUserRelatedByCreatedBy()->getEmail().' ('.$this->getUserRelatedByCreatedBy()->getFullName().')';
		$aTags = TagPeer::tagInstancesForObject($this);
		$aCategories = array();
		foreach($aTags as $oTag) {
			$aCategories[] = $oTag->getTagName();
		}
		$aResult[$bIsForRpc ? 'categories' : 'category'] = $aCategories;
		if($aJournalPageLink) {
			$aResult['guid'] = $aResult['link'];
		} else {
			$aResult['guid'] = array('isPermaLink' => 'false', '__content' => $this->getId()."-".$this->getJournal()->getId());
		}
		$aResult['pubDate'] = date(DATE_RSS, (int)$this->getCreatedAtTimestamp());
		if($bIsForRpc) {
			$aResult['dateCreated'] = new xmlrpcval(iso8601_encode((int)$this->getCreatedAtTimestamp()), 'dateTime.iso8601');
			$aResult['date_created_gmt'] = new xmlrpcval(iso8601_encode((int)$this->getCreatedAtTimestamp(), 1), 'dateTime.iso8601');
			$aResult['postid'] = $this->getId();
		} else {
			$aEnclosures = array();
			foreach($this->getImages() as $oImage) {
				$oDocument = $oImage->getDocument();
				$aEnclosures[] = array('length' => $oDocument->getDataSize(), 'type' => $oDocument->getMimetype(), 'url' => LinkUtil::absoluteLink($oDocument->getLink(), null, LinkUtil::isSSL()));
			}
			$aResult['enclosure'] = $aEnclosures;
		}
		return $aResult;
	}

	/**
	* Sets the journal entry text. When given a TagParser or an HtmlTag instance, this method will use the first paragraph tag found to construct the synopsis (short text).
	* @param string|TagParser|HtmlTag $mText
	*/
	public function setText($mText) {
		if($mText instanceof TagParser) {
			$mText = $mText->getTag();
		}
		if($mText instanceof HtmlTag) {
			$oTextShort = null;
			foreach($mText->getChildren() as $oChild) {
				if($oChild instanceof HtmlTag && strtolower($oChild->getName()) === 'p') {
					$oTextShort = $oChild;
					break;
				}
			}
			$mText = $mText->__toString();
			if($oTextShort) {
				$this->setTextShort($oTextShort->__toString());
			} else {
				$this->setTextShort($mText);
			}
		}
		parent::setText($mText);
	}

	public function getCountComments() {
		$iCount = $this->countJournalComments();
		if($iCount > 0) {
			return $iCount;
		}
		return '-';
	}

	public function getCountImages() {
		$iCount = $this->countJournalEntryImages();
		if($iCount > 0) {
			return $iCount;
		}
		return '-';
	}

	public function getJournalName() {
		if($oJournal = $this->getJournal()) {
			return $oJournal->getName();
		}
		return null;
	}

	public function getTitleTruncated($iTruncate = 50) {
		return StringUtil::truncate($this->getTitle(), $iTruncate);
	}

	public function getLink($oPage = null, $sSubpage = null) {
		if($oPage === null) {
			$oPage = $this->getJournal()->getJournalPage();
		}
		if($oPage === null) {
			return null;
		}
		if($sSubpage) {
			return $oPage->getLinkArray($this->getPublishAt('Y'), $this->getPublishAt('n'), $this->getPublishAt('j'), $this->getSlug(), $sSubpage);
		}
		return $oPage->getLinkArray($this->getPublishAt('Y'), $this->getPublishAt('n'), $this->getPublishAt('j'), $this->getSlug());
	}

	public function setTitle($sTitle) {
		if($this->isNew() || $this->getSlug() == null) {
			$this->setSlug(StringUtil::truncate(StringUtil::normalizePath($sTitle), 50, '', 0));
		}
		return parent::setTitle($sTitle);
	}

	public function fillFromRssAttributes($aAttributes) {
		if(isset($aAttributes['categories'])) {
			$aTags = $aAttributes['categories'];
			$aTagInstances = TagPeer::tagInstancesForObject($this);
			$aOldTags = array();
			foreach($aTagInstances as $oTagInstance) {
				if(!in_array($oTagInstance->getTagName(), $aTags)) {
					$oTagInstance->delete();
				} else {
					$aOldTags[] = $oTagInstance->getTagName();
				}
			}
			foreach($aTags as $sTagName) {
				if(!in_array($sTagName, $aOldTags)) {
					TagInstancePeer::newTagInstanceForObject($sTagName, $this);
				}
			}
		}
		$this->setText($aAttributes['description']);
		$this->setTitle($aAttributes['title']);
	}

	public function hasTags() {
		return $this->getHasTags();
	}

	public function getHasTags() {
		return TagQuery::create()->filterByTagged($this)->count() > 0;
	}

	public function setIsPublished($bIsPublished) {
		if($bIsPublished && !$this->getIsPublished()) {
			$this->setPublishAt(time());
		}
		return parent::setIsPublished($bIsPublished);
	}

	public function executeActionActivate() {
		$this->setIsPublished(true);
		$this->save();
	}

	public static function describeActionActivate() {
		return ActionDescriptor::create('journal.activate');
	}

} // JournalEntry
