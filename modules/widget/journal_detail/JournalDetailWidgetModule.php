<?php
/**
 * @package modules.widget
 */
class JournalDetailWidgetModule extends PersistentWidgetModule {

	private $iJournalId = null;
	private $oEntryListWidget = null;

	public function setJournalId($iJournalId) {
		if(is_array($iJournalId) && count($iJournalId) > 0) {
			$iJournalId = $iJournalId[0];
		}
		$this->iJournalId = $iJournalId;
		if($this->oEntryListWidget) {
			$this->oEntryListWidget->getListWidget()->getDelegate()->setJournalId($this->iJournalId);
		}
	}

	public function journalData() {
		$oJournal = JournalQuery::create()->findPk($this->iJournalId);
		$aResult = $oJournal->toArray();
		$aResult['CreatedInfo'] = Util::formatCreatedInfo($oJournal);
		$aResult['UpdatedInfo'] = Util::formatUpdatedInfo($oJournal);
		return $aResult;
	}

	public function entryList() {
		$this->oEntryListWidget = new JournalEntryListWidgetModule();
		$this->oEntryListWidget->getListWidget()->getDelegate()->setJournalId($this->iJournalId);
		$oIncluder = new ResourceIncluder();
		JournalEntryListWidgetModule::includeResources($oIncluder);
		return $oIncluder->getIncludes()->render().$this->oEntryListWidget->doWidget()->render();
	}

	private function validate($aJournalData) {
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
		$this->validate($aJournalData);
		$oJournal->setName($aJournalData['name']);
		$oJournal->setDescription($aJournalData['description']);
		$oJournal->setUseCaptcha($aJournalData['use_captcha']);
		$sCommentMode = $aJournalData['comment_mode'];
		$oJournal->setEnableComments($sCommentMode === 'on' || $sCommentMode === 'notified');
		$oJournal->setNotifyComments($sCommentMode === 'moderated' || $sCommentMode === 'notified');

		if(!Flash::noErrors()) {
			throw new ValidationException();
		}
		$oJournal->save();
		$oResult = new stdClass();
		if($this->iJournalId === null) {
			$oResult->inserted = true;
		} else {
			$oResult->updated = true;
		}
		$oResult->id = $this->iJournalId = $oJournal->getId();
		return $oResult;
	}
}