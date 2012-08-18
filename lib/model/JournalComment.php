<?php

require_once 'model/om/BaseJournalComment.php';

/**
 * @package model
 * @subpackage rapila-plugin-journal
 */	
class JournalComment extends BaseJournalComment {
	
	public function getCreatedAtLocalized($sFormat = ' %e. %B %Y') {
		if(Session::language() === 'en') {
			$sFormat = ' %e %B %Y';
		}
		return LocaleUtil::localizeDate($this->created_at, null, $sFormat);
	}
	
	public function preSave(PropelPDO $oConnection = null) {
		if($this->isNew()) {
			$this->setActivationHash(Util::uuid());
		}
		return parent::preSave($oConnection);
	}
}

