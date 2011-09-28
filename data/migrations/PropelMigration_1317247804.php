<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1317247804.
 * Generated on 2011-09-29 00:10:04 by rafi
 */
class PropelMigration_1317247804
{

	public function preUp($manager)
	{
		// add the pre-migration code here
	}

	public function postUp($manager)
	{
		// add the post-migration code here
	}

	public function preDown($manager)
	{
		// add the pre-migration code here
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


ALTER TABLE `journal_comments` ADD
(
	`updated_at` DATETIME,
	`created_by` INTEGER,
	`updated_by` INTEGER
);

CREATE INDEX `journal_comments_FI_2` ON `journal_comments` (`created_by`);

CREATE INDEX `journal_comments_FI_3` ON `journal_comments` (`updated_by`);

ALTER TABLE `journal_entries` ADD
(
	`updated_by` INTEGER
);

CREATE INDEX `journal_entries_FI_3` ON `journal_entries` (`updated_by`);

ALTER TABLE `journal_entry_images` ADD
(
	`created_at` DATETIME,
	`updated_at` DATETIME,
	`created_by` INTEGER,
	`updated_by` INTEGER
);

CREATE INDEX `journal_entry_images_FI_3` ON `journal_entry_images` (`created_by`);

CREATE INDEX `journal_entry_images_FI_4` ON `journal_entry_images` (`updated_by`);

ALTER TABLE `journals` ADD
(
	`created_at` DATETIME,
	`updated_at` DATETIME,
	`created_by` INTEGER,
	`updated_by` INTEGER
);

CREATE INDEX `journals_FI_1` ON `journals` (`created_by`);

CREATE INDEX `journals_FI_2` ON `journals` (`updated_by`);

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

ALTER TABLE `document_categories` DROP FOREIGN KEY `document_categories_FK_1`;

ALTER TABLE `document_categories` DROP FOREIGN KEY `document_categories_FK_2`;

ALTER TABLE `document_types` DROP FOREIGN KEY `document_types_FK_1`;

ALTER TABLE `document_types` DROP FOREIGN KEY `document_types_FK_2`;

ALTER TABLE `documents` DROP FOREIGN KEY `documents_FK_1`;

ALTER TABLE `documents` DROP FOREIGN KEY `documents_FK_2`;

ALTER TABLE `documents` DROP FOREIGN KEY `documents_FK_3`;

ALTER TABLE `documents` DROP FOREIGN KEY `documents_FK_4`;

ALTER TABLE `documents` DROP FOREIGN KEY `documents_FK_5`;

ALTER TABLE `documents` DROP FOREIGN KEY `documents_FK_6`;

ALTER TABLE `group_roles` DROP FOREIGN KEY `group_roles_FK_1`;

ALTER TABLE `group_roles` DROP FOREIGN KEY `group_roles_FK_2`;

ALTER TABLE `group_roles` DROP FOREIGN KEY `group_roles_FK_3`;

ALTER TABLE `group_roles` DROP FOREIGN KEY `group_roles_FK_4`;

ALTER TABLE `groups` DROP FOREIGN KEY `groups_FK_1`;

ALTER TABLE `groups` DROP FOREIGN KEY `groups_FK_2`;

ALTER TABLE `indirect_references` DROP FOREIGN KEY `indirect_references_FK_1`;

ALTER TABLE `indirect_references` DROP FOREIGN KEY `indirect_references_FK_2`;

ALTER TABLE `journal_comments` DROP FOREIGN KEY `journal_comments_FK_1`;

ALTER TABLE `journal_comments` DROP FOREIGN KEY `journal_comments_FK_2`;

ALTER TABLE `journal_comments` DROP FOREIGN KEY `journal_comments_FK_3`;

DROP INDEX `journal_comments_FI_2` ON `journal_comments`;

DROP INDEX `journal_comments_FI_3` ON `journal_comments`;

ALTER TABLE `journal_comments` DROP `updated_at`;

ALTER TABLE `journal_comments` DROP `created_by`;

ALTER TABLE `journal_comments` DROP `updated_by`;

ALTER TABLE `journal_entries` DROP FOREIGN KEY `journal_entries_FK_1`;

ALTER TABLE `journal_entries` DROP FOREIGN KEY `journal_entries_FK_2`;

ALTER TABLE `journal_entries` DROP FOREIGN KEY `journal_entries_FK_3`;

DROP INDEX `journal_entries_FI_3` ON `journal_entries`;

ALTER TABLE `journal_entries` DROP `updated_by`;

ALTER TABLE `journal_entry_images` DROP FOREIGN KEY `journal_entry_images_FK_1`;

ALTER TABLE `journal_entry_images` DROP FOREIGN KEY `journal_entry_images_FK_2`;

ALTER TABLE `journal_entry_images` DROP FOREIGN KEY `journal_entry_images_FK_3`;

ALTER TABLE `journal_entry_images` DROP FOREIGN KEY `journal_entry_images_FK_4`;

DROP INDEX `journal_entry_images_FI_3` ON `journal_entry_images`;

DROP INDEX `journal_entry_images_FI_4` ON `journal_entry_images`;

ALTER TABLE `journal_entry_images` DROP `created_at`;

ALTER TABLE `journal_entry_images` DROP `updated_at`;

ALTER TABLE `journal_entry_images` DROP `created_by`;

ALTER TABLE `journal_entry_images` DROP `updated_by`;

ALTER TABLE `journals` DROP FOREIGN KEY `journals_FK_1`;

ALTER TABLE `journals` DROP FOREIGN KEY `journals_FK_2`;

DROP INDEX `journals_FI_1` ON `journals`;

DROP INDEX `journals_FI_2` ON `journals`;

ALTER TABLE `journals` DROP `created_at`;

ALTER TABLE `journals` DROP `updated_at`;

ALTER TABLE `journals` DROP `created_by`;

ALTER TABLE `journals` DROP `updated_by`;

ALTER TABLE `language_object_history` DROP FOREIGN KEY `language_object_history_FK_1`;

ALTER TABLE `language_object_history` DROP FOREIGN KEY `language_object_history_FK_2`;

ALTER TABLE `language_object_history` DROP FOREIGN KEY `language_object_history_FK_3`;

ALTER TABLE `language_object_history` DROP FOREIGN KEY `language_object_history_FK_4`;

ALTER TABLE `language_objects` DROP FOREIGN KEY `language_objects_FK_1`;

ALTER TABLE `language_objects` DROP FOREIGN KEY `language_objects_FK_2`;

ALTER TABLE `language_objects` DROP FOREIGN KEY `language_objects_FK_3`;

ALTER TABLE `language_objects` DROP FOREIGN KEY `language_objects_FK_4`;

ALTER TABLE `languages` DROP FOREIGN KEY `languages_FK_1`;

ALTER TABLE `languages` DROP FOREIGN KEY `languages_FK_2`;

ALTER TABLE `link_categories` DROP FOREIGN KEY `link_categories_FK_1`;

ALTER TABLE `link_categories` DROP FOREIGN KEY `link_categories_FK_2`;

ALTER TABLE `links` DROP FOREIGN KEY `links_FK_1`;

ALTER TABLE `links` DROP FOREIGN KEY `links_FK_2`;

ALTER TABLE `links` DROP FOREIGN KEY `links_FK_3`;

ALTER TABLE `links` DROP FOREIGN KEY `links_FK_4`;

ALTER TABLE `links` DROP FOREIGN KEY `links_FK_5`;

ALTER TABLE `objects` DROP FOREIGN KEY `objects_FK_1`;

ALTER TABLE `objects` DROP FOREIGN KEY `objects_FK_2`;

ALTER TABLE `objects` DROP FOREIGN KEY `objects_FK_3`;

ALTER TABLE `page_properties` DROP FOREIGN KEY `page_properties_FK_1`;

ALTER TABLE `page_properties` DROP FOREIGN KEY `page_properties_FK_2`;

ALTER TABLE `page_properties` DROP FOREIGN KEY `page_properties_FK_3`;

ALTER TABLE `page_strings` DROP FOREIGN KEY `page_strings_FK_1`;

ALTER TABLE `page_strings` DROP FOREIGN KEY `page_strings_FK_2`;

ALTER TABLE `page_strings` DROP FOREIGN KEY `page_strings_FK_3`;

ALTER TABLE `page_strings` DROP FOREIGN KEY `page_strings_FK_4`;

ALTER TABLE `pages` DROP FOREIGN KEY `pages_FK_1`;

ALTER TABLE `pages` DROP FOREIGN KEY `pages_FK_2`;

ALTER TABLE `rights` DROP FOREIGN KEY `rights_FK_1`;

ALTER TABLE `rights` DROP FOREIGN KEY `rights_FK_2`;

ALTER TABLE `rights` DROP FOREIGN KEY `rights_FK_3`;

ALTER TABLE `rights` DROP FOREIGN KEY `rights_FK_4`;

ALTER TABLE `roles` DROP FOREIGN KEY `roles_FK_1`;

ALTER TABLE `roles` DROP FOREIGN KEY `roles_FK_2`;

ALTER TABLE `strings` DROP FOREIGN KEY `strings_FK_1`;

ALTER TABLE `strings` DROP FOREIGN KEY `strings_FK_2`;

ALTER TABLE `strings` DROP FOREIGN KEY `strings_FK_3`;

ALTER TABLE `tag_instances` DROP FOREIGN KEY `tag_instances_FK_1`;

ALTER TABLE `tag_instances` DROP FOREIGN KEY `tag_instances_FK_2`;

ALTER TABLE `tag_instances` DROP FOREIGN KEY `tag_instances_FK_3`;

ALTER TABLE `tags` DROP FOREIGN KEY `tags_FK_1`;

ALTER TABLE `tags` DROP FOREIGN KEY `tags_FK_2`;

ALTER TABLE `user_roles` DROP FOREIGN KEY `user_roles_FK_1`;

ALTER TABLE `user_roles` DROP FOREIGN KEY `user_roles_FK_2`;

ALTER TABLE `user_roles` DROP FOREIGN KEY `user_roles_FK_3`;

ALTER TABLE `user_roles` DROP FOREIGN KEY `user_roles_FK_4`;

ALTER TABLE `users` DROP FOREIGN KEY `users_FK_1`;

ALTER TABLE `users_groups` DROP FOREIGN KEY `users_groups_FK_1`;

ALTER TABLE `users_groups` DROP FOREIGN KEY `users_groups_FK_2`;

ALTER TABLE `users_groups` DROP FOREIGN KEY `users_groups_FK_3`;

ALTER TABLE `users_groups` DROP FOREIGN KEY `users_groups_FK_4`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
	}

}
