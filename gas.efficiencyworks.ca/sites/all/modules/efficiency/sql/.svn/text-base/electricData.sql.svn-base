DELETE FROM `civicrm_contact`;

-- Delete civi_group from new DB
DELETE FROM civicrm_group;

-- Inserting new Contact Sub type as "Applicant"
INSERT INTO `civicrm_contact_type` (`name` ,`label` ,`description` ,`image_URL` ,`parent_id` ,`is_active` ,`is_reserved`)VALUES ('Applicant', 'Applicant', 'Applicant', NULL , '1', '1', NULL);

-- Inserting new Contact Sub type as "Landlord"
INSERT INTO `civicrm_contact_type` (`name` ,`label` ,`description` ,`image_URL` ,`parent_id` ,`is_active` ,`is_reserved`)VALUES ('Landlord', 'Landlord', 'Landlord', NULL , '1', '1', NULL);

-- Inserting new Contact Sub type as "Admin"
INSERT INTO `civicrm_contact_type` (`name` ,`label` ,`description` ,`image_URL` ,`parent_id` ,`is_active` ,`is_reserved`)VALUES 
('Admin', 'Admin', 'Admin', NULL, 1, 1, NULL);

-- Inserting new Contact Sub type as "Auditor"
INSERT INTO `civicrm_contact_type` (`name` ,`label` ,`description` ,`image_URL` ,`parent_id` ,`is_active` ,`is_reserved`) VALUES 
('Auditor', 'Auditor', 'Auditor', NULL, 1, 1, NULL);

-- Inserting new Contact Sub type as "CSR"
INSERT INTO `civicrm_contact_type` (`name` ,`label` ,`description` ,`image_URL` ,`parent_id` ,`is_active` ,`is_reserved`)VALUES 
('CSR', 'CSR', 'CSR', NULL, 1, 1, NULL);

-- Inserting new Contact Sub type as "Retrofit"
INSERT INTO `civicrm_contact_type` (`name` ,`label` ,`description` ,`image_URL` ,`parent_id` ,`is_active` ,`is_reserved`)VALUES 
('Retrofit', 'Retrofit', 'Retrofit', NULL, 1, 1, NULL);

-- Inserting new Contact Sub type as "Outreach"
INSERT INTO `civicrm_contact_type` (`name` ,`label` ,`description` ,`image_URL` ,`parent_id` ,`is_active` ,`is_reserved`)VALUES 
('Outreach', 'Outreach', 'Outreach', NULL, 1, 1, NULL);

--  Inserting new Roles in new Drupal database  to assign contact as per contact sub type in old civi-DB
INSERT INTO `GCC-new_drupal`.`role`(`rid`,`name`,`weight`) 
VALUES 
( 4,'Admin','4'), 
( 6,'CSR','6'),
( 7,'Retrofit','7'),
( 8,'Auditor','8'),
( 9,'Outreach','9');

-- Inserting new Custom Search in Option Value for Option Group id 24 i.e. custom_search
SELECT @option_group_id := id from `civicrm_option_group` WHERE name LIKE '%custom_search%';

SELECT @max_weight      := max(weight) FROM `civicrm_option_value` WHERE option_group_id = @option_group_id;

INSERT INTO `civicrm_option_value` (`option_group_id`, `label`, `value`, `name`, `grouping`, `filter`, `is_default`, `weight`, `description`, `is_optgroup`, `is_reserved`, `is_active`, `component_id`, `domain_id`, `visibility_id`) 
VALUES
(@option_group_id, 'Efficiency_Form_Search_Custom_ListParticipant', @max_weight+1, 'Efficiency_Form_Search_Custom_ListParticipant', NULL, 0, 0, @max_weight+1, 'ListParticipant', 0, 0, 1, NULL, NULL, NULL);

-- Insert Option Group and respective Option Value for Project Details Status 
-- INSERT INTO `civicrm_option_group` (`name`, `title`, `description`, `is_reserved`, `is_active`) VALUES
-- ('project_details_status', 'Project Details Status', 'Project Details Status', 1, 1);

-- SELECT @option_group_id := id from `civicrm_option_group` WHERE name LIKE '%project_details_status%';

/*INSERT INTO `civicrm_option_value` (`option_group_id`, `label`, `value`, `name`, `grouping`, `filter`, `is_default`, `weight`, `description`, `is_optgroup`, `is_reserved`, `is_active`, `component_id`, `domain_id`, `visibility_id`) VALUES
(@option_group_id, 'Automatic', '0', 'automatic', NULL, 0, 1, 0, NULL, 1, 0, 1, NULL, NULL, NULL),
(@option_group_id, 'Closed - No potential', '15', 'closed_no_potential', NULL, 0, 0, 15, NULL, 0, 0, 1, NULL, NULL, NULL),
(@option_group_id, 'Closed - Participant withdrew', '16', 'closed_participant_withdrew', NULL, 0, 0, 16, NULL, 0, 0, 1, NULL, NULL, NULL),
(@option_group_id, 'New Participant', '12', 'new_participant', NULL, 0, 0, 12, NULL, 0, 0, 1, NULL, NULL, NULL),
(@option_group_id, 'Audit Assigned', '13', 'audit_assigned', NULL, 0, 0, 13, NULL, 0, 0, 1, NULL, NULL, NULL),
(@option_group_id, 'Retrofit Pending', '14', 'retrofit_pending', NULL, 0, 0, 14, NULL, 0, 0, 1, NULL, NULL, NULL),
(@option_group_id, 'Retrofit Completed', '17', 'retrofit_completed', NULL, 0, 0, 17, NULL, 0, 0, 1, NULL, NULL, NULL),
(@option_group_id, 'Project Completed', '20', 'project_completed', NULL, 0, 0, 20, NULL, 0, 0, 1, NULL, NULL, NULL),
(@option_group_id, 'Applicant', '11', 'applicant', NULL, 0, 0, 11, NULL, 0, 0, 1, NULL, NULL, NULL),
(@option_group_id, 'Ready for QA', '18', 'ready_for_QA', NULL, 0, 0, 18, NULL, 0, 0, 1, NULL, NULL, NULL),
(@option_group_id, 'Report to LDC', '19', 'report_to_LDC', NULL, 0, 0, 19, NULL, 0, 0, 1, NULL, NULL, NULL);*/

-- DELETE FROM `civicrm_uf_join` WHERE `civicrm_uf_join`.`uf_group_id` = 1 AND `civicrm_uf_join`.`module`='User Registration';
-- DELETE FROM `civicrm_uf_join` WHERE `civicrm_uf_join`.`uf_group_id` = 1 AND `civicrm_uf_join`.`module`='User Account';

-- To update custom group of landlord by applying subtype.
UPDATE `civicrm_custom_group` SET extends_entity_column_value='Landlord' WHERE name='Landlord_Custom_Group';

-- DISABLE Name & Address Profile from Drupal User Registration
UPDATE `civicrm_uf_group` SET `is_active` = 0 WHERE `title` LIKE "%Name and Address%" AND 
`group_type` = "Individual,Contact";


INSERT INTO `civicrm_group` (`id`, `name`, `title`, `description`, `source`, `saved_search_id`, `is_active`, `visibility`, `where_clause`, `select_tables`, `where_tables`, `group_type`, `cache_date`, `parents`, `children`, `is_hidden`) VALUES
(1, 'Administrators', 'Administrators', 'Contacts in this group are assigned Administrator role permissions.', NULL, NULL, 1, 'User and User Admin Only', NULL, NULL, NULL, '1', NULL, NULL, NULL, 0),
(2, 'XM', 'XM', NULL, NULL, NULL, 1, 'User and User Admin Only', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(3, 'PM', 'PM', NULL, NULL, NULL, 1, 'User and User Admin Only', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(4, 'Elora', 'Elora', NULL, NULL, NULL, 1, 'User and User Admin Only', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(5, 'Hamilton', 'Hamilton', NULL, NULL, NULL, 1, 'User and User Admin Only', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(6, 'Kingston', 'Kingston', NULL, NULL, NULL, 1, 'User and User Admin Only', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(7, 'Lanark', 'Lanark', NULL, NULL, NULL, 1, 'User and User Admin Only', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(8, 'Peterborough', 'Peterborough', NULL, NULL, NULL, 1, 'User and User Admin Only', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(9, 'Thunder Bay', 'Thunder Bay', NULL, NULL, NULL, 1, 'User and User Admin Only', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(10, 'Toronto', 'Toronto', NULL, NULL, NULL, 1, 'User and User Admin Only', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(11, 'Waterloo', 'Waterloo', NULL, NULL, NULL, 1, 'User and User Admin Only', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(12, 'York', 'York', NULL, NULL, NULL, 1, 'User and User Admin Only', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(13, 'Collingwood_13', 'Collingwood', NULL, NULL, NULL, 1, 'User and User Admin Only', ' ( `civicrm_group_contact-13`.group_id IN ( 13 ) AND `civicrm_group_contact-13`.status IN ("Added") ) ', 'a:12:{s:15:"civicrm_contact";i:1;s:15:"civicrm_address";i:1;s:22:"civicrm_state_province";i:1;s:15:"civicrm_country";i:1;s:13:"civicrm_email";i:1;s:13:"civicrm_phone";i:1;s:10:"civicrm_im";i:1;s:19:"civicrm_worldregion";i:1;s:26:"`civicrm_group_contact-13`";s:116:" LEFT JOIN civicrm_group_contact `civicrm_group_contact-13` ON contact_a.id = `civicrm_group_contact-13`.contact_id ";s:6:"gender";i:1;s:17:"individual_prefix";i:1;s:17:"individual_suffix";i:1;}', 'a:2:{s:15:"civicrm_contact";i:1;s:26:"`civicrm_group_contact-13`";s:116:" LEFT JOIN civicrm_group_contact `civicrm_group_contact-13` ON contact_a.id = `civicrm_group_contact-13`.contact_id ";}', NULL, NULL, NULL, NULL, 0);

-- ALTER TABLE `gcc_applicant` ADD `auto_status` INT( 11 ) NULL DEFAULT NULL;