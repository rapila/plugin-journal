<?php

require_once 'model/om/BaseJournalEntry.php';


/**
 * @package model
 */	
class JournalEntry extends BaseJournalEntry {

	public function getJournalComments($oCriteria = null, PropelPDO $oConnection = null) {
	  if($oCriteria === null) {
      $oCriteria = new Criteria();
	  }
    $oCriteria->addAscendingOrderByColumn(JournalCommentPeer::CREATED_AT);
    $oCriteria->addAscendingOrderByColumn(JournalCommentPeer::ID);
    return parent::getJournalComments($oCriteria, $oConnection);
	}
	
	public function getRssAttributes($oJournalPage = null, $bIsForRpc = false) {
    $aResult = array();
    $aResult['title'] = $this->getTitle();
    $aResult['link'] = LinkUtil::absoluteLink(LinkUtil::link($this->getLink($oJournalPage), 'FrontendManager'));
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
    $aResult['guid'] = $aResult['link'];
    $aResult['pubDate'] = date(DATE_RSS, (int)$this->getCreatedAtTimestamp());
    if($bIsForRpc) {
			$aResult['dateCreated'] = new xmlrpcval(iso8601_encode((int)$this->getCreatedAtTimestamp()), 'dateTime.iso8601');
			$aResult['date_created_gmt'] = new xmlrpcval(iso8601_encode((int)$this->getCreatedAtTimestamp(), 1), 'dateTime.iso8601');
      $aResult['postid'] = $this->getId();
    }
    return $aResult;
	}
	
	public function getCountComments() {
		return $this->countJournalComments();
	}
	
	public function getLatestCommentDate() {
		
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
		if($sSubpage) {
			return $oPage->getLinkArray($this->getCreatedAt('Y'), $this->getCreatedAt('m'), $this->getCreatedAt('d'), $this->getSlug(), $sSubpage);
		}
		return $oPage->getLinkArray($this->getCreatedAt('Y'), $this->getCreatedAt('m'), $this->getCreatedAt('d'), $this->getSlug());
	}

	public function setTitle($sTitle) {
		if($this->isNew()) {
			$this->setSlug(StringUtil::truncate(trim(StringUtil::normalize($sTitle), '-_'), 50, '', 0));
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

} // JournalEntry
