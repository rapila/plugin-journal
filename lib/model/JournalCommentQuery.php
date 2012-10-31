<?php

/**
 * @subpackage rapila-plugin-journal
 * @package		 propel.generator.model
 */
class JournalCommentQuery extends BaseJournalCommentQuery {
	
	public function excludeUnverified() {
		return $this->filterByIsPublished(true);
	}

	public function findHash($sHash) {
		return $this->filterByActivationHash($sHash)->findOne();
	}
	
	public function mostRecentFirst() {
		return $this->orderByCreatedAt(Criteria::DESC);
	}
	
}

