<?php
/**
 * @package modules.widget
 */
class JournalEntryListWidgetModule extends WidgetModule {

	private $oListWidget;
	private $oDelegateProxy;
	
	public function __construct() {
		$this->oListWidget = new ListWidgetModule();
		$this->oDelegateProxy = new CriteriaListWidgetDelegate($this, "JournalEntry", "created_at_formatted", "desc");
		$this->oListWidget->setDelegate($this->oDelegateProxy);
		$this->oListWidget->setSetting('row_model_drag_and_drop_identifier', "id");
	}
	
	public function getDelegate() {
		return $this->oDelegateProxy;
	}
	
	public function getList() {
		return $this->oListWidget;
	}
	
	public function doWidget() {
		return $this->oListWidget->doWidget('journal_entry_list');
	}

	public function getColumnIdentifiers() {
		$aColumns = array('id', 'title_truncated', 'created_at_formatted', 'count_comments', 'is_published');
		if($this->oDelegateProxy->getJournalId() === CriteriaListWidgetDelegate::SELECT_ALL) {
			$aColumns = array_merge($aColumns, array('journal_name'));
		}
		return array_merge($aColumns, array('delete'));
	}
	
	public function getMetadataForColumn($sColumnIdentifier) {
		$aResult = array('is_sortable' => true);
		switch($sColumnIdentifier) {
			case 'title_truncated':
				$aResult['heading'] = StringPeer::getString('wns.journal_entry.title');
				$aResult['field_name'] = 'title';
				break;
			case 'created_at_formatted':
				$aResult['heading'] = StringPeer::getString('wns.date');
				break;
			case 'is_published':
				$aResult['heading'] = StringPeer::getString('wns.journal_entry.is_published');
				break;
			case 'count_comments':
				$aResult['heading'] = StringPeer::getString('wns.journal_entry.count_comments');
				$aResult['is_sortable'] = false;
				break;
			case 'journal_name':
				$aResult['heading'] = StringPeer::getString('wns.journal.name');
				break;
			case 'delete':
				$aResult['heading'] = ' ';
				$aResult['display_type'] = ListWidgetModule::DISPLAY_TYPE_ICON;
				$aResult['field_name'] = 'trash';
				$aResult['is_sortable'] = false;
				break;
		}
		return $aResult;
	}

	public function getFilterTypeForColumn($sColumnIdentifier) {
		if($sColumnIdentifier === 'journal_id') {
			return CriteriaListWidgetDelegate::FILTER_TYPE_IN;
		}
		return null;
	}

	public function getDatabaseColumnForColumn($sColumnIdentifier) {
		if($sColumnIdentifier === 'created_at_formatted') {
			return JournalEntryPeer::CREATED_AT;
		}		
		if($sColumnIdentifier === 'journal_name') {
			return JournalPeer::NAME;
		}
		return null;
	}
	
	public function getJournalName() {
		$oJournal = JournalPeer::retrieveByPK($this->oDelegateProxy->getJournalId());
		if($oJournal) {
			return $oJournal->getName();
		}
		return $this->oDelegateProxy->getJournalId();
	}
	
	public function getCriteria() {
		$oQuery = JournalEntryQuery::create()->joinJournal();
		return $oQuery;
	}
}
