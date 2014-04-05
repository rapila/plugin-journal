<?php
/**
 * @package modules.admin
 */
class JournalEntriesAdminModule extends AdminModule {

	private $oListWidget;
	private $oSidebarWidget;
	private $oInputWidget;

	public function __construct() {
		$this->oListWidget = new JournalEntryListWidgetModule();
		if(isset($_REQUEST['journal_id'])) {
			$this->oListWidget->getDelegate()->setJournalId($_REQUEST['journal_id']);
		}
		
		$this->oSidebarWidget = new ListWidgetModule();
		$this->oSidebarWidget->setListTag(new TagWriter('ul'));
		$this->oSidebarWidget->setDelegate(new CriteriaListWidgetDelegate($this, 'Journal', 'name'));
		
		$this->oInputWidget = new SidebarInputWidgetModule();
	}
	
	public function mainContent() {
		return $this->oListWidget->doWidget();
	}
	
	public function sidebarContent() {
		return $this->oSidebarWidget->doWidget();
	}
	
	public function getColumnIdentifiers() {
		return array('journal_id', 'name', 'magic_column');
	}
	
	public function getMetadataForColumn($sColumnIdentifier) {
		$aResult = array();
		switch($sColumnIdentifier) {
			case 'journal_id':
				$aResult['display_type'] = ListWidgetModule::DISPLAY_TYPE_DATA;
				$aResult['field_name'] = 'id';
				break;
			case 'name':
				$aResult['heading'] = StringPeer::getString('wns.journal_entries.sidebar_heading');
				break;
			case 'magic_column':
				$aResult['display_type'] = ListWidgetModule::DISPLAY_TYPE_CLASSNAME;
				$aResult['has_data'] = false;
				break;
		}
		return $aResult;
	}
	
	public function getCustomListElements() {
		if(JournalQuery::create()->count() > 0) {
			return array(
			         array('journal_id' => CriteriaListWidgetDelegate::SELECT_ALL,
			               'name' => StringPeer::getString('wns.sidebar.select_all'),
			               'magic_column' => 'all'
			         )
			);
		}
		return array();
	}

	public function usedWidgets() {
		return array($this->oListWidget, $this->oSidebarWidget, $this->oInputWidget);
	}
}
