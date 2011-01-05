<?php
require_once('xmlrpc/xmlrpc.php');
require_once('xmlrpc/xmlrpcs.php');

class JournalApiFileModule extends FileModule {
  private $oServer;
  
  public function __construct($aRequestPath) {
    global $xmlrpcInt, $xmlrpcString, $xmlrpcStruct, $xmlrpc_internalencoding;
    parent::__construct($aRequestPath);
    
    $this->oServer = new xmlrpc_server(array('metaWeblog.newPost' => array('function' => 'JournalApiFileModule::newPost'),
                                             'metaWeblog.editPost' => array('function' => 'JournalApiFileModule::editPost'),
                                             'metaWeblog.getPost' => array('function' => 'JournalApiFileModule::getPost'),
                                             'metaWeblog.getRecentPosts' => array('function' => 'JournalApiFileModule::getRecentPosts'),
                                             'metaWeblog.getUserInfo' => array('function' => 'JournalApiFileModule::getUserInfo'),
                                             'metaWeblog.getCategories' => array('function' => 'JournalApiFileModule::getCategories'),
                                             'blogger.getUserInfo' => array('function' => 'JournalApiFileModule::getUserInfo'),
                                             'metaWeblog.getUsersBlogs' => array('function' => 'JournalApiFileModule::getUsersBlogs'),
                                             'blogger.getUsersBlogs' => array('function' => 'JournalApiFileModule::getUsersBlogs')
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
  
  //Ignore Blog-ID
  public static function newPost($iBlogId, $sUserName, $sPassword, $aPublish) {
    global $xmlrpcerruser;
    $iAdminTest = Session::getSession()->login($sUserName, $sPassword);
    if(($iAdminTest & Session::USER_IS_VALID) !== Session::USER_IS_VALID) {
      return new xmlrpcresp(0, $xmlrpcerruser+1, "Login incorrect");
    }
    $oJournalEntry = new JournalEntry();
    $oJournalEntry->fillFromRssAttributes($aPublish);
    $oJournalEntry->save();
    return $oJournalEntry->getId();
  }
  
  public static function editPost($iJournalEntryId, $sUserName, $sPassword, $aPublish) {
    global $xmlrpcerruser;
    $iAdminTest = Session::getSession()->login($sUserName, $sPassword);
    if(($iAdminTest & Session::USER_IS_VALID) !== Session::USER_IS_VALID) {
      return new xmlrpcresp(0, $xmlrpcerruser+1, "Login incorrect");
    }
    $oJournalEntry = JournalEntryPeer::retrieveByPk($iJournalEntryId);
    if($oJournalEntry === null) {
      return new xmlrpcresp(0, $xmlrpcerruser+1, "No Entry with id $iJournalEntryId");
    }
    $oJournalEntry->fillFromRssAttributes($aPublish);
    $oJournalEntry->save();
    return true;
  }
  
  public static function getPost($iJournalEntryId, $sUserName, $sPassword) {
    global $xmlrpcerruser;
    $iAdminTest = Session::getSession()->login($sUserName, $sPassword);
    if(($iAdminTest & Session::USER_IS_VALID) !== Session::USER_IS_VALID) {
      return new xmlrpcresp(0, $xmlrpcerruser+1, "Login incorrect");
    }
    $oJournalEntry = JournalEntryPeer::retrieveByPk($iJournalEntryId);
    if($oJournalEntry === null) {
      return new xmlrpcresp(0, $xmlrpcerruser+1, "No Entry with id $iJournalEntryId");
    }
    return $oJournalEntry->getRssAttributes(PagePeer::getRootPage()->getPageOfType('journal'), true);
  }
  
  public static function getUserInfo($sApiKey, $sUserName, $sPassword) {
    global $xmlrpcerruser;
    $iAdminTest = Session::getSession()->login($sUserName, $sPassword);
    if(($iAdminTest & Session::USER_IS_VALID) !== Session::USER_IS_VALID) {
      return new xmlrpcresp(0, $xmlrpcerruser+1, "Login incorrect");
    }
    $oUser = Session::getSession()->getUser();
    return array('nickname' => $oUser->getUsername(), 'userid' => $oUser->getId(), 'email' => $oUser->getEmail(), 'firstname' => $oUser->getFirstName(), 'lastname' => $oUser->getLastName());
  }
  
  public static function getUsersBlogs($sApiKey, $sUserName, $sPassword) {
    global $xmlrpcerruser;
    $iAdminTest = Session::getSession()->login($sUserName, $sPassword);
    if(($iAdminTest & Session::USER_IS_VALID) !== Session::USER_IS_VALID) {
      return new xmlrpcresp(0, $xmlrpcerruser+1, "Login incorrect");
    }
    $oJournalPage = PagePeer::getRootPage()->getPageOfType('journal');
    return array(array('url' => LinkUtil::absoluteLink(LinkUtil::link($oJournalPage->getFullPathArray(), 'FrontendManager')), 'blogid' => 0, 'blogName' => $oJournalPage->getPageTitle()));
  }
  
  //Ignore Blog-ID
  public static function getRecentPosts($iBlogId, $sUserName, $sPassword, $iCount) {
    global $xmlrpcerruser;
    $iAdminTest = Session::getSession()->login($sUserName, $sPassword);
    if(($iAdminTest & Session::USER_IS_VALID) !== Session::USER_IS_VALID) {
      return new xmlrpcresp(0, $xmlrpcerruser+1, "Login incorrect");
    }
    $aJournalEntries = JournalEntryPeer::getMostRecentEntries($iCount);
    $aResult = array();
    $oJournalPage = PagePeer::getRootPage()->getPageOfType('journal');
    foreach($aJournalEntries as $oJournalEntry) {
      $aResult[] = $oJournalEntry->getRssAttributes($oJournalPage, true);
    }
    return $aResult;
  }
  
  //Ignore Blog-ID
  public static function getCategories($iBlogId, $sUserName, $sPassword) {
    global $xmlrpcerruser;
    $iAdminTest = Session::getSession()->login($sUserName, $sPassword);
    if(($iAdminTest & Session::USER_IS_VALID) !== Session::USER_IS_VALID) {
      return new xmlrpcresp(0, $xmlrpcerruser+1, "Login incorrect");
    }
    $oCriteria = new Criteria();
    $oCriteria->add(TagInstancePeer::MODEL_NAME, 'JournalEntry');
    $aTagInstances = TagInstancePeer::doSelect($oCriteria);
    $aTags = array();
    foreach($aTagInstances as $oTagInstance) {
      $sTagName = $oTagInstance->getTagName();
      if(isset($aTags[$sTagName])) {
        $aTags[$sTagName]++;
      } else {
        $aTags[$sTagName] = 1;
      }
    }
    return array_keys($aTags);
  }
}