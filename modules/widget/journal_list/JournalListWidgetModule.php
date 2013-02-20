<?php
/**
 * @package modules.widget
 */
class JournalListWidgetModule extends WidgetModule {

	private $oListWidget;
	private $oDelegateProxy;
	
	public function __construct() {
		$this->oListWidget = new ListWidgetModule();
		$this->oDelegateProxy = new CriteriaListWidgetDelegate($this, "Journal", "name", "asc");
		$this->oListWidget->setDelegate($this->oDelegateProxy);
	}
	
	public function doWidget() {
		return $this->oListWidget->doWidget('journal_list');
	}

	public function getColumnIdentifiers() {
		return array('id', 'name', 'enable_comments', 'notify_comments', 'use_captcha', 'count_entries', 'delete');
	}
	
	public function getMetadataForColumn($sColumnIdentifier) {
		$aResult = array('is_sortable' => true);
		switch($sColumnIdentifier) {
			case 'name':
				$aResult['heading'] = StringPeer::getString('wns.journal.name');
				break;
			case 'enable_comments':
				$aResult['heading'] = StringPeer::getString('wns.journal.enable_comments');
				break;
			case 'notify_comments':
				$aResult['heading'] = StringPeer::getString('wns.journal.notify_comments');
				break;
			case 'use_captcha':
				$aResult['heading'] = StringPeer::getString('wns.journal.use_captcha');
				break;
			case 'count_entries':
				$aResult['heading'] = StringPeer::getString('wns.journal.count_entries');
				$aResult['is_sortable'] = false;
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
}
