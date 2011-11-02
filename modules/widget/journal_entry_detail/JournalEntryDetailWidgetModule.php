<?php
class JournalEntryDetailWidgetModule extends PersistentWidgetModule {
	private $iJournalId;
	private $iJournalEntryId;
	
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

	public function setJournalEntryId($iJournalEntryId) {
		$this->iJournalEntryId = $iJournalEntryId;
	}
	
	public function getElementType() {
		return "form";
	}
	
	public function loadData() {
		$aResult = JournalEntryPeer::retrieveByPK($this->iJournalEntryId);
		if(!$aResult) {
			return;
		}
		$aResult = $aResult->toArray();
		$aResult['Text'] = RichtextUtil::parseStorageForBackendOutput($aResult['Text'])->render();
		$aResult['comments'] = array();
		foreach(JournalCommentQuery::create()->filterByJournalEntryId($this->iJournalEntryId)->orderByCreatedAt(Criteria::DESC)->find() as $oComment) {
			$aComment = array();
			$aComment['CreatedAtFormatted'] = $oComment->getCreatedAtFormatted();
			$aComment['Email'] = $oComment->getEmail();
			$aComment['Text'] = $oComment->getText();
			$aResult['comments'][] = $aComment;
		}
		return $aResult;
	}
	
	public function saveData($aData) {
		$oEntry = JournalEntryPeer::retrieveByPK($this->iJournalEntryId);
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
