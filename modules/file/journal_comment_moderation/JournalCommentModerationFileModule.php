<?php
class JournalCommentModerationFileModule extends FileModule {

	private $oJournalComment;
	private $sAction;
	
	public function __construct($aRequestPath) {
		parent::__construct($aRequestPath);
		$this->oJournalComment = JournalCommentQuery::create()->findHash(Manager::usePath());
		$this->sAction = Manager::usePath();
	}
	
	public function renderFile() {
		if($this->oJournalComment === null) {
			throw new Exception('Hash invalid');
		}
		JournalCommentPeer::ignoreRights(true);
		$sAction = StringUtil::camelize("comment_$this->sAction");
		$this->$sAction();
		echo 'done';
	}

	public function commentDeactivate() {
		$this->oJournalComment->setIsPublished(false);
		$this->oJournalComment->save();
	}
	
	public function commentActivate() {
		$this->oJournalComment->setIsPublished(true);
		$this->oJournalComment->save();
	}

	public function commentDelete() {
		if($this->oJournalComment->getIsPublished()) {
			throw new Exception('Comment must be disabled in order to be deleted');
		}
		$this->oJournalComment->delete();
	}
}
