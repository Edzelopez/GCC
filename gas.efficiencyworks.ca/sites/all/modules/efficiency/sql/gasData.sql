
DELETE FROM `civicrm_contact`;

-- Delete civi_group from new DB
DELETE FROM `civicrm_group`;

-- Delete Contact sub type's from Civicrm
DELETE FROM `civicrm_contact_type` WHERE name = 'Student';
DELETE FROM `civicrm_contact_type` WHERE name = 'Parent';
DELETE FROM `civicrm_contact_type` WHERE name = 'Staff';

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

--  Inserting new Roles in new Drupal database  to assign contact as per contact sub type in old civi-DB
INSERT INTO `GCC-new_drupal`.`role`(`rid`,`name`,`weight`) VALUES ( 4,'Admin','4'), 
( 5,'CSR','5'),( 6,'Retrofit','6'),( 7,'Auditor','7');


-- Inserting new Custom Search in Option Value for Option Group id 24 i.e. custom_search
SELECT @option_group_id := id from `civicrm_option_group` WHERE name LIKE '%custom_search%';

SELECT @max_weight      := max(weight) FROM `civicrm_option_value` WHERE option_group_id = @option_group_id;

INSERT INTO `civicrm_option_value` (`option_group_id`, `label`, `value`, `name`, `grouping`, `filter`, `is_default`, `weight`, `description`, `is_optgroup`, `is_reserved`, `is_active`, `component_id`, `domain_id`, `visibility_id`) 
VALUES
(@option_group_id, 'Efficiency_Form_Search_Custom_ListParticipant', @max_weight+1, 'Efficiency_Form_Search_Custom_ListParticipant', NULL, 0, 0, @max_weight+1, 'ListParticipant', 0, 0, 1, NULL, NULL, NULL);

-- DISABLE Name & Address Profile from Drupal User Registration
UPDATE `civicrm_uf_group` SET `is_active` = 0 WHERE `title` LIKE "%Name and Address%" AND 
`group_type` = "Individual,Contact";

-- Insert Message template
INSERT INTO `civicrm_msg_template` (`msg_title`, `msg_subject`, `msg_text`, `msg_html`, `is_active`, `workflow_id`, `is_default`, `is_reserved`, `pdf_format_id`) VALUES ('New Enbridge Audit', 'New Enbridge Audit', NULL, '<p>\r\n	A new participant has been assigned for an audit.<br />\r\n	Please go to www.efficiencyworks.ca <http://www.efficiencyworks.ca> and pick up the files.</p>', 1, NULL, 1, NULL, NULL);

-- Inserting Report

SELECT @optgrpID      :=`id` FROM `civicrm_option_group` WHERE `title` LIKE '%report_template%';

INSERT INTO `civicrm_option_value` (`option_group_id`, `label`, `value`, `name`, `grouping`, `filter`, `is_default`, `weight`, `description`, `is_optgroup`, `is_reserved`, `is_active`, `component_id`, `domain_id`, `visibility_id`) VALUES ( @optgrpID, 'GCAudit Report', 'efficiency/gcaudit', 'Efficiency_Form_Report_GCAudit', NULL, 0, 0, 1, 'GCAudit Report', 0, 0, 1, NULL, NULL, NULL);

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

