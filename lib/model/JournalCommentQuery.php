<?php


/**
 * Skeleton subclass for performing query and update operations on the 'journal_comments' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.model
 */
class JournalCommentQuery extends BaseJournalCommentQuery {
	public function excludeUnverified() {
		return $this->filterByIsPublished(true);
	}

	public function findHash($sHash) {
		return $this->filterByActivationHash($sHash)->findOne();
	}
} // JournalCommentQuery
