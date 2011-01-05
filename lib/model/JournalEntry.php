<?php

require_once 'model/om/BaseJournalEntry.php';


/**
 * Skeleton subclass for representing a row from the 'journal_entries' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package model
 */	
class JournalEntry extends BaseJournalEntry {

  public function save(PropelPDO $oConnection = null) {
    if($this->isNew()) {
      $this->setName(strftime('%Y-%m-%d-').trim(StringUtil::truncate(StringUtil::normalize($this->getTitle()), 50, '', 0), '-_'));
    }
    return parent::save($oConnection);
  }
  
  public function delete(PropelPDO $oConnection = null) {
    TagPeer::deleteTagsForObject($this);
    return parent::delete($oConnection);
  }
  
	public function getJournalComments($oCriteria = null, PropelPDO $oConnection = null) {
	  if($oCriteria === null) {
      $oCriteria = new Criteria();
	  }
    $oCriteria->addAscendingOrderByColumn(JournalCommentPeer::CREATED_AT);
    $oCriteria->addAscendingOrderByColumn(JournalCommentPeer::ID);
    return parent::getJournalComments($oCriteria, $oConnection);
	}
	
	public function getRssAttributes($oJournalPage, $bIsForRpc = false) {
    $aResult = array();
    $aResult['title'] = $this->getTitle();
    $aResult['link'] = LinkUtil::absoluteLink(JournalPageTypeModule::getJournalLink($oJournalPage, $this));
    $aResult['description'] = RichtextUtil::parseStorageForFrontendOutput($this->getText())->render();
    $aResult['author'] = $this->getUser()->getEmail().' ('.$this->getUser()->getFullName().')';
    $aTags = TagPeer::tagInstancesForObject($this);
    $aCategories = array();
    foreach($aTags as $oTag) {
      $aCategories[] = $oTag->getTagName();
    }
    $aResult[$bIsForRpc ? 'categories' : 'category'] = $aCategories;
    $aResult['guid'] = $aResult['link'];
    $aResult['pubDate'] = date(DATE_RFC2822, $this->created_at);
    if($bIsForRpc) {
      $aResult['postid'] = $this->getId();
    }
    return $aResult;
	}
	
	public function fillFromRssAttributes($aAttributes) {
	  if(isset($aAttributes['categories'])) {
      $aTags = $aAttributes['categories'];
      $aTagInstances = TagPeer::tagInstancesForObject($this);
      $aOldTags = array();
      foreach($oTagInstances as $oTagInstance) {
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
