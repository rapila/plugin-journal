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
	
	public function listEntries($iJournalId) {
		$oJournalEntryList = new JournalEntryListWidgetModule();
		$oJournalEntryList->getDelegate()->setJournalId($iJournalId);
		
		$oIncluder = new ResourceIncluder();
		JournalEntryListWidgetModule::includeResources($oIncluder);

		return $oIncluder->getIncludes()->render().$oJournalEntryList->doWidget()->render();
	}

	public function saveData($aJournalData) {
		if($this->iJournalId === null) {
			$oJournal = new Journal();
		} else {
			$oJournal = JournalQuery::create()->findPk($this->iJournalId);
		}
		$sCommentMode = $aJournalData['comment_mode'];
		$oJournal->fromArray($aJournalData, BasePeer::TYPE_FIELDNAME);
		$oJournal->setEnableComments($sCommentMode === 'on' || $sCommentMode === 'notified');
		$oJournal->setNotifyComments($sCommentMode === 'moderated' || $sCommentMode === 'notified');
		$this->validate($aJournalData, $oJournal);
		if(!Flash::noErrors()) {
			throw new ValidationException();
		}
		
		$oJournal->save();
		return $oJournal->getId();
	}
}