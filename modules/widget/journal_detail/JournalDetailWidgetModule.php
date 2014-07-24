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
			$this->oEntryListWidget->getDelegate()->setJournalId($this->iJournalId);
		}
	}

	public function journalData() {
		$oJournal = JournalQuery::create()->findPk($this->iJournalId);
		$aResult = $oJournal->toArray();
		$aResult['CreatedInfo'] = Util::formatCreatedInfo($oJournal);
		$aResult['UpdatedInfo'] = Util::formatUpdatedInfo($oJournal);
		return $aResult;
	}

	private function validate($aJournalData) {
		$oFlash = Flash::getFlash();
		$oFlash->setArrayToCheck($aJournalData);
		$oFlash->checkForValue('name', 'name_required');
		$oFlash->finishReporting();
	}

	public function entryList() {
		$this->oEntryListWidget = new JournalEntryListWidgetModule();
		$this->oEntryListWidget->getDelegate()->setJournalId($this->iJournalId);
		$oIncluder = new ResourceIncluder();
		JournalEntryListWidgetModule::includeResources($oIncluder);
		return $oIncluder->getIncludes()->render().$this->oEntryListWidget->doWidget()->render();
	}

	public function saveData($aJournalData) {
		if($this->iJournalId === null) {
			$oJournal = new Journal();
		} else {
			$oJournal = JournalQuery::create()->findPk($this->iJournalId);
		}
		$oJournal->setName($aJournalData['name']);
		$oJournal->setDescription($aJournalData['description']);
		$oJournal->setUseCaptcha($aJournalData['use_captcha']);
		$sCommentMode = $aJournalData['comment_mode'];
		$oJournal->setEnableComments($sCommentMode === 'on' || $sCommentMode === 'notified');
		$oJournal->setNotifyComments($sCommentMode === 'moderated' || $sCommentMode === 'notified');

		$this->validate($aJournalData);
		if(!Flash::noErrors()) {
			throw new ValidationException();
		}
		$oJournal->save();
		return $oJournal->getId();
	}
}