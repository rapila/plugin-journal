<?php
/**
 * @package modules.admin
 */
class JournalsAdminModule extends AdminModule {

	private $oListWidget;

	public function __construct() {
		$this->oListWidget = new JournalListWidgetModule();
	}
	
	public function mainContent() {
		return $this->oListWidget->doWidget();
	}
	
	public function sidebarContent() {
		return null;
	}

	public function usedWidgets() {
		return array($this->oListWidget);
	}
}
