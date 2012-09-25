<?php

require_once($_SERVER['PWD'].'/base/lib/inc.php');

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1348568740.
 * Generated on 2012-09-25 12:25:40 by rafi
 */
class PropelMigration_1348568740
{

    public function preUp($manager)
    {
        // add the pre-migration code here
    }

    public function postUp($manager)
    {
			foreach(PageQuery::create()->filterByPageType('journal')->find() as $oJournalPage) {
				$sCommentMode = $oJournalPage->getPagePropertyValue('blog_comment_mode', 'on');
				$aJournalIds = explode(',', $oJournalPage->getPagePropertyValue('blog_journal_id', ''));
				$bCaptchaEnabled = !!$oJournalPage->getPagePropertyValue('blog_captcha_enabled', true);
				foreach(JournalQuery::create()->filterById($aJournalIds)->find() as $oJournal) {
					$oJournal->setEnableComments($sCommentMode === 'on' || $sCommentMode === 'notified');
					$oJournal->setNotifyComments($sCommentMode === 'moderated' || $sCommentMode === 'notified');
					$oJournal->setUseCaptcha($bCaptchaEnabled);
					$oJournal->save();
				}
				$oJournalPage->updatePageProperty('blog_comment_mode', null);
				$oJournalPage->updatePageProperty('blog_captcha_enabled', null);
			}
        // add the post-migration code here
    }

    public function preDown($manager)
    {
        // add the pre-migration code	 here
    }

    public function postDown($manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'rapila' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `journals`
    ADD `enable_comments` TINYINT(1) DEFAULT 1 AFTER `description`,
    ADD `notify_comments` TINYINT(1) DEFAULT 0 AFTER `enable_comments`,
    ADD `use_captcha` TINYINT(1) DEFAULT 1 AFTER `notify_comments`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'rapila' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `journals` DROP `enable_comments`;
ALTER TABLE `journals` DROP `notify_comments`;
ALTER TABLE `journals` DROP `use_captcha`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}