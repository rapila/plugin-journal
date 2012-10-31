<?php
/**
* Implements the MetaWeblog API for all journals on the current site. Point your blog editor to the MetaWeblog endpoint of /get_file/journal_api to use this.
* @todo implement metaWeblog.newMediaObject
*/
class JournalApiFileModule extends FileModule {
	private $oServer;
	
	public function __construct($aRequestPath) {
		global $xmlrpcInt, $xmlrpcString, $xmlrpcStruct, $xmlrpc_internalencoding;
		parent::__construct($aRequestPath);

		require_once('xmlrpc/xmlrpc.php');
		require_once('xmlrpc/xmlrpcs.php');
		
		$this->oServer = new xmlrpc_server(array('metaWeblog.newPost' => array('function' => 'JournalApiFileModule::newPost'),
																						'metaWeblog.editPost' => array('function' => 'JournalApiFileModule::editPost'),
																						'metaWeblog.getPost' => array('function' => 'JournalApiFileModule::getPost'),
																						'metaWeblog.getRecentPosts' => array('function' => 'JournalApiFileModule::getRecentPosts'),
																						'metaWeblog.getUserInfo' => array('function' => 'JournalApiFileModule::getUserInfo'),
																						'metaWeblog.getCategories' => array('function' => 'JournalApiFileModule::getCategories'),
																						'blogger.getUserInfo' => array('function' => 'JournalApiFileModule::getUserInfo'),
																						'blogger.getUsersBlogs' => array('function' => 'JournalApiFileModule::getUsersBlogs'),
																						'blogger.deletePost' => array('function' => 'JournalApiFileModule::deletePost')
																						), false
																			);
		
		$this->oServer->functions_parameters_type = 'phpvals';
		$this->oServer->response_charset_encoding = 'UTF-8';
		$xmlrpc_internalencoding = 'UTF-8';
		$this->oServer->compress_response = false;
	}
	
	public function renderFile() {
		$this->oServer->service();
	}
	
	public static function newPost($iBlogId, $sUserName, $sPassword, $aPublish) {
		if(!self::checkLogin($sUserName, $sPassword)) {
			return self::loginError();
		}
		$oJournalEntry = new JournalEntry();
		$oJournalEntry->setJournalId($iBlogId);
		$oJournalEntry->fillFromRssAttributes($aPublish);
		$oJournalEntry->save();
		return $oJournalEntry->getId();
	}
	
	public static function editPost($iJournalEntryId, $sUserName, $sPassword, $aPublish) {
		if(!self::checkLogin($sUserName, $sPassword)) {
			return self::loginError();
		}
		$oJournalEntry = JournalEntryPeer::retrieveByPk($iJournalEntryId);
		if($oJournalEntry === null) {
			return self::error("No Entry with id $iJournalEntryId", 2);
		}
		$oJournalEntry->fillFromRssAttributes($aPublish);
		$oJournalEntry->save();
		return true;
	}
	
	public static function deletePost($sApiKey, $iJournalEntryId, $sUserName, $sPassword) {
		if(!self::checkLogin($sUserName, $sPassword)) {
			return self::loginError();
		}
		$oJournalEntry = JournalEntryPeer::retrieveByPk($iJournalEntryId);
		if($oJournalEntry === null) {
			return self::error("No Entry with id $iJournalEntryId", 2);
		}
		return $oJournalEntry->delete();
	}
	
	public static function getPost($iJournalEntryId, $sUserName, $sPassword) {
		if(!self::checkLogin($sUserName, $sPassword)) {
			return self::loginError();
		}
		$oJournalEntry = JournalEntryQuery::create()->findPk($iJournalEntryId);
		if($oJournalEntry === null) {
			return self::error("No Entry with id $iJournalEntryId", 2);
		}
		return $oJournalEntry->getRssAttributes(PagePeer::getRootPage()->getPageOfType('journal'), true);
	}
	
	public static function getUserInfo($sApiKey, $sUserName, $sPassword) {
		if(!self::checkLogin($sUserName, $sPassword)) {
			return self::loginError();
		}
		$oUser = Session::getSession()->getUser();
		return array('nickname' => $oUser->getUsername(), 'userid' => $oUser->getId(), 'email' => $oUser->getEmail(), 'firstname' => $oUser->getFirstName(), 'lastname' => $oUser->getLastName());
	}
	
	public static function getUsersBlogs($sApiKey, $sUserName, $sPassword) {
		if(!self::checkLogin($sUserName, $sPassword)) {
			return self::loginError();
		}
		$aJournals = array();
		foreach(JournalQuery::create()->find() as $oJournal) {
			$oJournalPage = $oJournal->getJournalPage();
			$aLink = $oJournalPage ? $oJournalPage->getLinkArray() : array();
			$aJournals[] = array('url' => LinkUtil::absoluteLink(LinkUtil::link($aLink, 'FrontendManager')), 'blogid' => $oJournal->getId(), 'blogName' => $oJournal->getName());
		}
		return $aJournals;
	}
	
	public static function getRecentPosts($iBlogId, $sUserName, $sPassword, $iCount) {
		if(!self::checkLogin($sUserName, $sPassword)) {
			return self::loginError();
		}
		$oQuery = JournalEntryQuery::create()->filterByJournalId($iBlogId)->mostRecentFirst()->limit($iCount);
		$oJournalPage = JournalQuery::create()->findPk($iBlogId)->getJournalPage();
		$aResult = array();
		foreach($oQuery->find() as $oJournalEntry) {
			$aResult[] = $oJournalEntry->getRssAttributes($oJournalPage, true);
		}
		return $aResult;
	}
	
	//Ignore Blog-ID
	public static function getCategories($iBlogId, $sUserName, $sPassword) {
		if(!self::checkLogin($sUserName, $sPassword)) {
			return self::loginError();
		}
		$aTags = array();
		foreach(TagInstanceQuery::create()->filterByModelName('JournalEntry')->find() as $oTagInstance) {
			$sTagName = $oTagInstance->getTagName();
			if(isset($aTags[$sTagName])) {
				$aTags[$sTagName]++;
			} else {
				$aTags[$sTagName] = 1;
			}
		}
		return array_keys($aTags);
	}

	private static function error($sMessage, $iCode) {
		global $xmlrpcerruser;
		return new xmlrpcresp(0, $xmlrpcerruser+$iCode, $sMessage);
	}

	private static function loginError() {
		return self::error('Login incorrect', 1);
	}

	private static function checkLogin($sUserName, $sPassword) {
		$iAdminTest = Session::getSession()->login($sUserName, $sPassword);
		return ($iAdminTest & Session::USER_IS_VALID) === Session::USER_IS_VALID;
	}
}
