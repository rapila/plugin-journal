<?php

require_once 'model/om/BaseJournalComment.php';


/**
 * Skeleton subclass for representing a row from the 'journal_comments' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package model
 */	
class JournalComment extends BaseJournalComment {
	public function preSave(PropelPDO $oConnection = null) {
		if($this->isNew()) {
			$this->setActivationHash(Util::uuid());
		}
		return parent::preSave($oConnection);
	}
} // JournalComment
