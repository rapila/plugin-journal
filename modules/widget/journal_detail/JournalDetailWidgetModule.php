<?php
/**
 * @package modules.widget
 */
class JournalDetailWidgetModule extends PersistentWidgetModule {

	private $iJournalId = null;
	
	public function setJournalId($iJournalId) {
		if(is_array($iJournalId) && count($iJournalId) > 0) {
			$iJournalId = $iJournalId[0];
		}
		$this->iJournalId = $iJournalId;
	}
	
	public function journalData() {
		$oJournal = JournalQuery::create()->findPk($this->iJournalId);
		$aResult = $oJournal->toArray();
		$aResult['CreatedInfo'] = Util::formatCreatedInfo($oJournal);
		$aResult['UpdatedInfo'] = Util::formatUpdatedInfo($oJournal);
		return $aResult;
	}
		
	private function validate($aJournalData, $oJournal) {
		$oFlash = Flash::getFlash();
		$oFlash->setArrayToCheck($aJournalData);
		$oFlash->checkForValue('name', 'name_required');
		$oFlash->finishReporting();
	}

	public function saveData($aJournalData) {
		if($this->iJournalId === null) {
			$oJournal = new Journal();
		} else {
			$oJournal = JournalQuery::create()->findPk($this->iJournalId);
		}
		$oJournal->fromArray($aJournalData, BasePeer::TYPE_FIELDNAME);
		$this->validate($aJournalData, $oJournal);
		if(!Flash::noErrors()) {
			throw new ValidationException();
		}
		
		$oJournal->save();
		return $oJournal->getId();
	}
}