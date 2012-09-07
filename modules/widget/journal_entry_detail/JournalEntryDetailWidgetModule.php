<?php
class JournalEntryDetailWidgetModule extends PersistentWidgetModule {
	
	private $oRichTextWidget;
	
	private $iJournalId;
	
	private $iJournalEntryId;
	
	public function __construct($sSessionKey = null, $oPage = null) {
		parent::__construct($sSessionKey);
		$this->oRichTextWidget = WidgetModule::getWidget('rich_text', null, null, 'journal');
		if($oPage) {
			$this->oRichTextWidget->setTemplate($oPage->getTemplateNameUsed());
		}
		$this->setSetting('richtext_session', $this->oRichTextWidget->getSessionKey());
		
		$iJournalEntryImageCategory = Settings::getSetting('journal', 'externally_managed_images_category', null);
		$this->setSetting('journal_entry_images_category_id', $iJournalEntryImageCategory);
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
	
	public function toggleCommentIsPublished($iCommentId) {
		$oComment = JournalCommentQuery::create()->findPk($iCommentId);
		if($oComment) {
			$oComment->setIsPublished(!$oComment->getIsPublished());
			$oComment->save();
			return $oComment->getIsPublished();
		}
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

	public function saveData($aData) {
		$oEntry = JournalEntryPeer::retrieveByPK($this->iJournalEntryId);
		if($oEntry === null) {
			$oEntry = new JournalEntry();
			$oEntry->setJournalId($this->iJournalId);
		}
		$oEntry->setTitle($aData['title']);
		$oEntry->setIsPublished($aData['is_published']);
		$oRichtextUtil = new RichtextUtil();
		$oRichtextUtil->setTrackReferences($oEntry);
		$oEntry->setText($oRichtextUtil->getTagParser($aData['text']));

		return $oEntry->save();
	}
}
