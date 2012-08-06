<?php

  // include base peer class
  require_once 'model/om/BaseJournalCommentPeer.php';
  
  // include object class
  include_once 'model/JournalComment.php';


/**
 * @package model
 */	
class JournalCommentPeer extends BaseJournalCommentPeer {
	
	public static function mayOperateOn($oUser, $mObject, $sOperation) {
		if(parent::mayOperateOn($oUser, $mObject, $sOperation)) {
			return true;
		}
		return $mObject->isNew();
	}

}

