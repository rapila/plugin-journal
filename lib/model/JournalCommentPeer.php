<?php

  // include base peer class
  require_once 'model/om/BaseJournalCommentPeer.php';
  
  // include object class
  include_once 'model/JournalComment.php';


/**
 * Skeleton subclass for performing query and update operations on the 'journal_comments' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package model
 */	
class JournalCommentPeer extends BaseJournalCommentPeer {
	public static function mayOperateOn($oUser, $mObject, $sOperation) {
		if(parent::mayOperateOn($oUser, $mObject, $sOperation)) {
			return true;
		}
		return $mObject->isNew();
	}

} // JournalCommentPeer
