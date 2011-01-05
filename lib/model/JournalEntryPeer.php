<?php

  // include base peer class
  require_once 'model/om/BaseJournalEntryPeer.php';
  
  // include object class
  include_once 'model/JournalEntry.php';


/**
 * @package model
 */	
class JournalEntryPeer extends BaseJournalEntryPeer {
  public static function retrieveByName($sName) {
    $oCriteria = new Criteria();
    $oCriteria->add(self::NAME, $sName);
    return self::doSelectOne($oCriteria);
  }
  
  public static function getMostRecentEntries($iLimit = null, $iJournalId=null) {
    $oCriteria = new Criteria();
    if($iJournalId !== null) {
      $oCriteria->add(self::JOURNAL_ID, $iJournalId);
    }
    $oCriteria->addDescendingOrderByColumn(self::CREATED_AT);
    if($iLimit !== null) {
      $oCriteria->setLimit($iLimit);
    }
    return self::doSelect($oCriteria);
  }
  
  public static function getMostRecentEntry($iJournalId=null) {
    $aEntries = self::getMostRecentEntries(1, $iJournalId);
    if(isset($aEntries[0])) {
      return $aEntries[0];
    }
    return null;
  }

}

