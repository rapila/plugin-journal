<?php
class JournalEntryDetailWidgetModule extends PersistentWidgetModule {
	private $iJournalId;
	private $iEntryId;
	
	public function __construct($sSessionKey = null, $oPage = null) {
		parent::__construct($sSessionKey);
		$this->oRichTextWidget = WidgetModule::getWidget('rich_text', null, null, 'journal');
		if($oPage) {
			$this->oRichTextWidget->setTemplate($oPage->getTemplateNameUsed());
		}
		$this->setSetting('richtext_session', $this->oRichTextWidget->getSessionKey());
	}

	public function setJournalId($iJournalId) {
		$this->iJournalId = $iJournalId;
	}

	public function setEntryId($iEntryId) {
		$this->iEntryId = $iEntryId;
	}
	
	public function getElementType() {
		return "form";
	}
	
	public function loadData() {
		$aResult = JournalEntryPeer::retrieveByPK($this->iEntryId);
		if(!$aResult) {
			return;
		}
		$aResult = $aResult->toArray();
		$aResult['Text'] = RichtextUtil::parseStorageForBackendOutput($aResult['Text'])->render();
		$aResult['comments'] = array();
		foreach(JournalCommentQuery::create()->filterByJournalEntryId($this->iEntryId)->orderByCreatedAt()->find() as $oComment) {
			$aResult['comments'][] = $oComment->toArray();
		}
		return $aResult;
	}
	
	public function saveData($aData) {
		$oEntry = JournalEntryPeer::retrieveByPK($this->iEntryId);
		if($oEntry === null) {
			$oEntry = new JournalEntry();
			$oEntry->setJournalId($this->iJournalId);
		}
		$oEntry->setTitle($aData['title']);
		$oRichtextUtil = new RichtextUtil();
		$oRichtextUtil->setTrackReferences($oEntry);
		$oEntry->setText($oRichtextUtil->parseInputFromMce($aData['text']));
		return $oEntry->save();
	}
}
