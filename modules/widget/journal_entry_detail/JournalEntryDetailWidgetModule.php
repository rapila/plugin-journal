<?php
class JournalEntryDetailWidgetModule extends PersistentWidgetModule {
	
	private $oRichTextWidget;
	
	private $iJournalId;
	
	private $iJournalEntryId;
	
	public function __construct($sSessionKey = null, $oPage = null) {
		parent::__construct($sSessionKey);
		$this->oRichTextWidget = WidgetModule::getWidget('rich_text', null, null, 'journal');

		if($oPage === null) {
			// get any blog page in site
			$oPage = PageQuery::create()->filterByPageType('journal')->joinPageProperty()->useQuery('PageProperty')->filterByName('journal:journal_id')->endUse()->findOne();
		} 
		if($oPage) {
			$this->oRichTextWidget->setTemplate($oPage->getTemplateNameUsed());
		}
		$this->setSetting('richtext_session', $this->oRichTextWidget->getSessionKey());
		
		$iJournalEntryImageCategory = Settings::getSetting('journal', 'externally_managed_images_category', null);
		$this->setSetting('journal_entry_images_category_id', $iJournalEntryImageCategory);
		$this->setSetting('date_today', date('d.m.Y'));
		$this->setSetting('date_format', 'dd.mm.yy');
	}

	public function setJournalId($iJournalId) {
		if(is_numeric($iJournalId)){
			$this->iJournalId = $iJournalId;
		}
		$this->iJournalId = null;
	}

	public function setJournalEntryId($iJournalEntryId) {
		$this->iJournalEntryId = $iJournalEntryId;
	}
	
	public function getElementType() {
		return "form";
	}
	
	public function toggleCommentIsPublished($iCommentId) {
		$oComment = JournalCommentQuery::create()->findPk($iCommentId);
		if($oComment) {
			$oComment->setIsPublished(!$oComment->getIsPublished());
			$oComment->save();
			return $oComment->getIsPublished();
		}
	}
	
	public function loadData() {
		$oJournalEntry = JournalEntryPeer::retrieveByPK($this->iJournalEntryId);
		if(!$oJournalEntry) {
			return;
		}
		$aResult = array();
		$aResult = $oJournalEntry->toArray();
		$aResult['Text'] = RichtextUtil::parseStorageForBackendOutput($aResult['Text'])->render();
		$aResult['PublishAt'] = $oJournalEntry->getPublishAt('d.m.Y');
		$aResult['CreatedInfo'] = Util::formatCreatedInfo($oJournalEntry);
		$aResult['UpdatedInfo'] = Util::formatUpdatedInfo($oJournalEntry);
		$aResult['comments'] = array();
		foreach(JournalCommentQuery::create()->filterByJournalEntryId($this->iJournalEntryId)->orderByCreatedAt(Criteria::DESC)->find() as $oComment) {
			$aComment = array();
			$aComment['CreatedAtFormatted'] = $oComment->getCreatedAtFormatted();
			$aComment['Id'] = $oComment->getId();
			$aComment['Email'] = $oComment->getEmail();
			$aComment['Text'] = $oComment->getText();
			$aComment['IsPublished'] = $oComment->getIsPublished();
			$aResult['comments'][] = $aComment;
		}
		return $aResult;
	}
	
	public function addJournalEntryImage($iDocumentId) {
		if($this->iJournalEntryId === null) {
			$this->aUnsavedDocuments[] = $iDocumentId;
			return;
		}
		if(JournalEntryImagePeer::retrieveByPK($this->iJournalEntryId, $iDocumentId)) {
			return;
		}
		$oJournalEntryImage = new JournalEntryImage();
		$oJournalEntryImage->setJournalEntryId($this->iJournalEntryId);
		$oJournalEntryImage->setDocumentId($iDocumentId);
		return $oJournalEntryImage->save();
	}
	
	public function allDocuments($iThumbnailSize = 180) {
		$aDocuments = JournalEntryImageQuery::create()->filterByJournalEntryId($this->iJournalEntryId)->joinDocument()->orderBySort()->find();
		$aResult = array();
		foreach($aDocuments as $oEntryDocument) {
			$aResult[] = $this->rowData($oEntryDocument->getDocument(), $iThumbnailSize);
		}
		return $aResult;
	}
	
	public function rowData($oDocument, $iThumbnailSize = 180) {
		return array( 'Name' => $oDocument->getName(), 
									'Id' => $oDocument->getId(), 
									'Preview' => $oDocument->getPreview($iThumbnailSize)
								);
	}
	
	public function getSingleDocument($iDocumentId, $iThumbnailSize) {
		$oDokument = DocumentPeer::retrieveByPK($iDocumentId);
		if($oDokument) {
			return $this->rowData($oDokument, $iThumbnailSize);
		}
		return null;
	}
	
	public function deleteDocument($iDocumentId) {
		$oDocument = DocumentPeer::retrieveByPK($iDocumentId);
		if($oDocument && JournalEntryImageQuery::create()->filterByDocument($oDocument)->filterByJournalEntryId($this->iJournalEntryId)->findOne()) {
			return $oDocument->delete();
		}
	}
	
	public function reorderDocuments($aDocumentIds) {
		foreach($aDocumentIds as $iCount => $iDocumentId) {
			$oDocument = JournalEntryImagePeer::retrieveByPK($this->iJournalEntryId, $iDocumentId);
			$oDocument->setSort($iCount+1);
			$oDocument->save();
		}
	}
	
	private function validate($oData, $oJournalEntry) {
		$oFlash = Flash::getFlash();
		$oFlash->setArrayToCheck($oData);
		$oFlash->checkForValue('title', 'journal_entry.title_required');
		$oFlash->checkForValue('journal_id', 'journal_entry.journal_id_required');
		if($oJournalEntry->getIsPublished()) {
			$oFlash->checkForValue('text', 'journal_entry.text_required');
			$oJournalEntry->setIsPublished('false');
		}
		ErrorHandler::log("journal entry validate", $oData);
		$oFlash->finishReporting();
	}
	
	public function saveData($aData) {
		$oJournalEntry = JournalEntryPeer::retrieveByPK($this->iJournalEntryId);
		if($oJournalEntry === null) {
			$oJournalEntry = new JournalEntry();
			$oJournalEntry->setJournalId($this->iJournalId);
		}
		if($aData['publish_at'] == null) {
			$aData['publish_at'] = date('c');
		} 		
		$oJournalEntry->fromArray($aData, BasePeer::TYPE_FIELDNAME);
		
		if(isset($aData['journal_id'])) {
			$oJournalEntry->setJournalId($aData['journal_id']);
		}
		$this->validate($aData, $oJournalEntry);
		if(!Flash::noErrors()) {
			throw new ValidationException();
		}
		$oRichtextUtil = new RichtextUtil();
		$oRichtextUtil->setTrackReferences($oJournalEntry);
		$oJournalEntry->setText($oRichtextUtil->getTagParser($aData['text']));

		return $oJournalEntry->save();
	}
}
