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
		echo TranslationPeer::getString("journal_comment.executed_$this->sAction", null, 'done');
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
		// TODO: disable delete button and
		if($this->oJournalComment->getIsPublished()) {
			throw new Exception("Published comments can't be deleted");
		}
		$this->oJournalComment->delete();
	}
}
