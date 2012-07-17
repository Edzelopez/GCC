-- Moving Civi-contact from old database to new one with Contact sub type as Applicant 
TRUNCATE TABLE gas_civicrm.`civicrm_contact`;
INSERT INTO  gas_civicrm.`civicrm_contact`
  (`id`,`contact_type`,`contact_sub_type`,`do_not_email`,`do_not_phone`,`do_not_mail`,
 `do_not_sms`,`do_not_trade`,`is_opt_out`,`legal_identifier`,`external_identifier`,`sort_name`,
 `display_name`,`nick_name`,`legal_name`,`image_URL`,`preferred_communication_method`,`preferred_language`,`preferred_mail_format`,
 `hash`,`api_key`,`source`,`first_name`,`middle_name`,`last_name`,`prefix_id`,`suffix_id`,
 `email_greeting_id`,`email_greeting_custom`,`email_greeting_display`,
 `postal_greeting_id`,`postal_greeting_custom`,`postal_greeting_display`,
 `addressee_id`,`addressee_custom`,`addressee_display`,
 `job_title`,`gender_id`,`birth_date`,`is_deceased`,`deceased_date`,`household_name`,`primary_contact_id`,
 `organization_name`,`sic_code`,`user_unique_id`,`employer_id`,`is_deleted`) 
   SELECT cc.id,'Individual' AS contact_type,
  CASE WHEN cc.contact_sub_type != '(NULL)'
            THEN cc.contact_sub_type 
            ELSE 'Applicant' END AS contact_sub_type,cc.do_not_email,cc.do_not_phone,cc.do_not_mail,
  NULL AS do_not_sms,cc.do_not_trade,cc.is_opt_out,cc.legal_identifier,cc.external_identifier,cc.sort_name,
  cc.display_name,cc.nick_name,legal_name,cc.image_URL,cc.preferred_communication_method,'Both' AS preferred_language ,cc.preferred_mail_format,
  cc.hash,NULL AS api_key,cc.source,ci.first_name,ci.middle_name,ci.last_name,ci.prefix_id,ci.suffix_id,
  NULL AS email_greeting_id,NULL AS email_greeting_custom,NULL AS email_greeting_display,
  NULL AS postal_greeting_id,NULL AS postal_greeting_custom,NULL AS postal_greeting_display,
  NULL AS addressee_id,NULL AS addressee_custom,NULL AS addressee_display,
  ci.job_title,ci.gender_id,ci.birth_date,ci.is_deceased,ci.deceased_date,ch.household_name,ch.primary_contact_id,
  co.organization_name,co.sic_code,NULL AS user_unique_id,NULL AS employer_id,0 AS is_deleted  
  FROM eworks_civicrm.`civicrm_contact` AS cc 
  LEFT JOIN eworks_civicrm.`civicrm_individual` ci ON cc.id = ci.contact_id 
  LEFT JOIN eworks_civicrm.`civicrm_household` ch ON cc.id = ch.contact_id 
  LEFT JOIN eworks_civicrm.`civicrm_organization` co ON cc.id = co.contact_id;

-- Updating contacts to set contact sub type to NULL where contactType=Individual

-- UPDATE `gas_civicrm`.`civicrm_contact` SET contact_sub_type="NULL" WHERE contact_type="Individual";
  
-- Comparing and Updating UF_match table w.r.t user id in drupal database
INSERT INTO `gas_civicrm`.`civicrm_uf_match`
 (`domain_id`,`uf_id`,`uf_name`,`contact_id`,`LANGUAGE`)
 SELECT 1 AS domain_id, uf.uf_id, uf.email, uf.contact_id, 'en_US' AS LANGUAGE
FROM eworks_civicrm.civicrm_uf_match AS uf;
  
  
-- Updating new civi_address table from old civicrm_location and address
TRUNCATE TABLE `gas_civicrm`.`civicrm_address`;  
INSERT INTO `gas_civicrm`.`civicrm_address`
(`contact_id`,`location_type_id`,`is_primary`,`is_billing`,`street_address`,
`street_number`,`street_number_suffix`,`street_number_predirectional`,`street_name`,`street_type`,`street_number_postdirectional`,
`street_unit`,`supplemental_address_1`,`supplemental_address_2`,`supplemental_address_3`,`city`,`county_id`,`state_province_id`,
`postal_code_suffix`,`postal_code`,`usps_adc`,`country_id`,`geo_code_1`,`geo_code_2`,`timezone`,`name`,`master_id`)  
SELECT cl.entity_id,cl.location_type_id,cl.is_primary,0 AS is_billing,ca.street_address,
ca.street_number,ca.street_number_suffix,ca.street_number_predirectional,ca.street_name,ca.street_type,ca.street_number_postdirectional,
ca.street_unit,ca.supplemental_address_1,ca.supplemental_address_2,ca.supplemental_address_3,ca.city,ca.county_id,ca.state_province_id,
ca.postal_code_suffix,ca.postal_code,ca.usps_adc,ca.country_id,ca.geo_code_1,ca.geo_code_2,ca.timezone,cl.name,NULL AS master_id
FROM eworks_civicrm.`civicrm_address` AS ca 
LEFT JOIN eworks_civicrm.`civicrm_location` AS cl ON cl.id = ca.location_id;


--  Updating GCC-Applicant table in new database
--  Mapped 
--  id        -> applicant_id
--  entity_id -> contact_id
INSERT INTO `gas_civicrm`.`gcc_applicant`
(`entity_id`,`file_identifier`,`date_entered`,`occupants`,`adults`,`planguage`,`is_pay_heat`,`is_pay_elec`,
`ldc_id`,`ldc_acct`,`gas_util_id`,`gas_acct`,`central_air_id`,`tenure`,`pheat_fuel_id`,`dhw_fuel_id`,`house_type_id`,
`income_basis_id`,`verified_by`,`referral_id`,`corrections`,`landlord_release_id`,`contact_evaluation_id`,
`qa_status`,`Status`)
SELECT ga.contact_id,ga.file_identifier,ga.date_entered,ga.occupants,ga.adults,ga.planguage,ga.is_pay_heat,ga.is_pay_elec,
ga.ldc_id,ga.ldc_acct,ga.gas_util_id,ga.gas_acct,ga.central_air_id,ga.tenure,ga.pheat_fuel_id,ga.dhw_fuel_id,ga.house_type_id,
ga.income_basis_id,ga.verified_by,ga.referral_id,ga.corrections,ga.landlord_release_id,ga.contact_evaluation_id,
ga.qa_status,gas.Status 
FROM eworks_civicrm.`gcc_applicant` AS ga 
LEFT JOIN eworks_drupal.gcc_applicant_status AS gas ON  gas.id = ga.id;

--  Updating GCC-Measures table in new database
--  Mapped same as above 
INSERT INTO `gas_civicrm`.`gcc_measures`
(`id`,`entity_id`,`measures`,`name`,`funder`,`installed`,`costs`,`kwh`,`kw_s`,`kw_w`,`m3saved`,
`l_oil`,`l_propane`,`npv`,`life_profile`) 
SELECT gm.id,ga.contact_id,gm.measures,gm.name,gm.funder,gm.installed,gm.costs,gm.kwh,gm.kw_s,gm.kw_w,gm.m3saved,
gm.l_oil,gm.l_propane,gm.npv,gm.life_profile
FROM eworks_civicrm.`gcc_measures` AS gm
LEFT JOIN eworks_civicrm.`gcc_applicant` AS ga ON ga.id = gm.applicant_id;

--  Updating GCC-Misc table in new database
--  Mapped same as above
INSERT INTO `gas_civicrm`.`gcc_misc`(`entity_id`,`audit_invoiced`,`retrofit_invoiced`) 
 SELECT ga.contact_id,gm.audit_invoiced,gm.retrofit_invoiced
 FROM eworks_civicrm.`gcc_misc` AS gm
 LEFT JOIN eworks_civicrm.`gcc_applicant` AS ga ON ga.id = gm.applicant_id;
 
--  Updating GCC-Retrofit table in new database
--  Mapped same as above
 INSERT INTO `gas_civicrm`.`gcc_retrofit`
(`id`,`entity_id`,`measures_id`,`xm_workorder_issued`,`xm_installed`,`xm_verified`,`xm_pay_authorized`) 
SELECT NULL,ga.contact_id,gr.measures_id,gr.xm_workorder_issued,gr.xm_installed,gr.xm_verified,gr.xm_pay_authorized
FROM eworks_civicrm.gcc_retrofit AS gr
LEFT JOIN eworks_civicrm.`gcc_measures` AS gm ON gm.id = gr.measures_id
LEFT JOIN eworks_civicrm.`gcc_applicant` AS ga ON ga.id = gm.applicant_id;

--  Updating GCC-Measures Other table in new database
--  Mapped same as above
INSERT INTO `gas_civicrm`.`gcc_measures_other`
(`entity_id`,`audit_completed`,`retrofit_completed`,`audit_type_id`,`wac`,`computers`,`fridges`,`frzrs`,`htg_sys`,`shhd_flow`,`shhd_flow_after`,`software`,`potential_costs`,`potential_kwh`,`potential_kw_s`,`potential_kw_w`,`potential_m3saved`,`potential_l_oil`,`potential_l_propane`,`potential_npv`,`job_costs`,`job_kwh`,`job_kw_s`,`job_kw_w`,`job_m3saved`,`job_l_oil`,`job_l_propane`,`job_npv`,`bm_costs`,`bm_kwh`,`bm_kw_s`,`bm_kw_w`,`bm_trc`,`xm_costs`,`xm_kwh`,`xm_kw_s`,`xm_kw_w`,`xm_trc`,`basic_m3`,`extended_m3`,`audit_type_text`,`base_gas_m3`,`job_bcr`,`ft2`)
 SELECT ga.contact_id,gm.`audit_completed`,gm.`retrofit_completed`,gm.`audit_type_id`,gm.`wac`,gm.`computers`,gm.`fridges`,
gm.`frzrs`,gm.`htg_sys`,gm.`shhd_flow`,gm.`shhd_flow_after`,gm.`software`,gm.`potential_costs`,gm.`potential_kwh`,gm.`potential_kw_s`,
gm.`potential_kw_w`,gm.`potential_m3saved`,gm.`potential_l_oil`,gm.`potential_l_propane`,gm.`potential_npv`,gm.`job_costs`,
gm.`job_kwh`,gm.`job_kw_s`,gm.`job_kw_w`,gm.`job_m3saved`,gm.`job_l_oil`,gm.`job_l_propane`,gm.`job_npv`,gm.`bm_costs`,
gm.`bm_kwh`,gm.`bm_kw_s`,gm.`bm_kw_w`,gm.`bm_trc`,gm.`xm_costs`,gm.`xm_kwh`,gm.`xm_kw_s`,gm.`xm_kw_w`,gm.`xm_trc`,gm.`basic_m3`,
gm.`extended_m3`,gm.`audit_type_text`,gm.`base_gas_m3`,gm.`job_bcr`,gm.`ft2`
FROM eworks_civicrm.`gcc_measures_other` AS gm
LEFT JOIN eworks_civicrm.`gcc_applicant` AS ga ON ga.id = gm.applicant_id; 

--  Inserting name column value as contact in new Civi contact table
--  For now we have inserted contact_sub type as 6 which will needed for next query to create relationship
INSERT INTO `gas_civicrm`.`civicrm_contact` (contact_type,`contact_sub_type`,`display_name`) 
SELECT 'Individual' AS contact_type,cc.location_type_id , cc.name FROM eworks_civicrm.civicrm_location cc WHERE cc.location_type_id=6 AND cc.name !=" ";

-- Insert first & last name fields from display_name field
-- UPDATE `gas_civicrm`.`civicrm_contact` SET `first_name` = SUBSTRING_INDEX(SUBSTRING_INDEX(display_name, ' ', 1), ' ', -1), last_name = SUBSTRING_INDEX(SUBSTRING_INDEX(display_name, ' ', 2), ' ', -1)
-- WHERE `contact_sub_type` LIKE '%Landlord%';


-- Creating relationship as "Landlord of" after executing above query
INSERT INTO `gas_civicrm`.`civicrm_relationship` (contact_id_a,contact_id_b,relationship_type_id)
SELECT cc.id AS contact_a_id,ct.entity_id AS contact_b_id,ct.location_type_id FROM `gas_civicrm`.civicrm_contact cc LEFT JOIN eworks_civicrm.civicrm_location ct ON (cc.display_name = ct.name AND ct.location_type_id = cc.contact_sub_type) WHERE ct.name !="" AND cc.contact_sub_type =6 AND cc.display_name!="";

-- Updating rel_type_id to 10 which is inserted w.r.t XMl file
UPDATE `gas_civicrm`.`civicrm_relationship` SET relationship_type_id =12;

-- Now Updating that subtype to Landlord from 6
UPDATE `gas_civicrm`.`civicrm_contact`  cc SET cc.contact_sub_type="Landlord" WHERE cc.contact_sub_type=6;

-- Updating new civi-group table w.r.t old Db
TRUNCATE TABLE `gas_civicrm`.`civicrm_group`;
INSERT INTO `gas_civicrm`.`civicrm_group`
(`id`,`name`,`title`,`description`,`source`,`saved_search_id`,`is_active`,`visibility`,`where_clause`,`select_tables`,`where_tables`,`group_type`,`cache_date`,`parents`,`children`,`is_hidden`) 
SELECT cg.`id`,cg.`name`,cg.`title`,cg.`description`,cg.`source`,cg.`saved_search_id`,cg.`is_active`,cg.`visibility`,cg.`where_clause`,cg.`select_tables`,cg.`where_tables`,cg.`group_type`,NULL,NULL,NULL,0
FROM eworks_civicrm.`civicrm_group` AS cg;

-- Updating new civi-group contact table w.r.t old Db
INSERT INTO `gas_civicrm`.`civicrm_group_contact`
(`group_id`,`contact_id`,`status`,`location_id`,`email_id`)
SELECT cgc.`group_id`,cgc.`contact_id`,cgc.`status`,cgc.`location_id`,cgc.`email_id`
FROM `eworks_civicrm`.`civicrm_group_contact` AS cgc;

-- Updating email table of new Civi db
TRUNCATE TABLE `gas_civicrm`.`civicrm_email`;
INSERT INTO `gas_civicrm`.`civicrm_email`
(`contact_id`,`location_type_id`,`email`,`is_primary`,`is_billing`,`on_hold`,`is_bulkmail`,
`hold_date`,`reset_date`,`signature_text`,`signature_html`) 
SELECT cl.`entity_id`,cl.`location_type_id`,ce.`email`,ce.`is_primary`,0 AS `is_billing`,ce.`on_hold`,ce.`is_bulkmail`,
ce.`hold_date`,ce.`reset_date`,NULL AS `signature_text`,NULL AS`signature_html` 
FROM eworks_civicrm.civicrm_email AS ce 
LEFT JOIN eworks_civicrm.civicrm_location AS cl ON cl.id = ce.location_id;

-- Updating file table of new Civi db
 INSERT INTO `gas_civicrm`.`civicrm_file`
(`id`,`file_type_id`,`mime_type`,`uri`,`document`,`description`,`upload_date`) 
SELECT * FROM eworks_civicrm.`civicrm_file`;


-- Updating entity file table in new civi Db
INSERT INTO `gas_civicrm`.`civicrm_entity_file`
(`id`,`entity_table`,`entity_id`,`file_id`)
SELECT * FROM eworks_civicrm.`civicrm_entity_file`;

-- Updating Note
INSERT INTO `gas_civicrm`.`civicrm_note`
(`entity_table`,`entity_id`,`note`,`contact_id`,`modified_date`,`subject`,`privacy`) 
SELECT cn.`entity_table`,cn.`contact_id`,cn.`note`,cn.`contact_id`,cn.`modified_date`,cn.`subject`,NULL AS `privacy` 
FROM eworks_civicrm.civicrm_note AS cn;
UPDATE `gas_civicrm`.`civicrm_note` as cn SET cn.`subject` = 'note' WHERE cn.`subject` IS NULL;

-- Updating Log table in new Civi Db
INSERT INTO `gas_civicrm`.`civicrm_log`
(`entity_table`,`entity_id`,`data`,`modified_id`,`modified_date`) 
SELECT cl.`entity_table`,cl.`entity_id`,cl.`data`,cl.`modified_id`,cl.`modified_date`
FROM eworks_civicrm.`civicrm_log` AS cl;

--  Updating phone table in new civi DB
TRUNCATE TABLE `gas_civicrm`.`civicrm_phone`;
INSERT INTO `gas_civicrm`.`civicrm_phone`
(`contact_id`,`location_type_id`,`is_primary`,`is_billing`,`mobile_provider_id`,`phone`,`phone_ext`,`phone_type_id`) 
SELECT cl.`entity_id`,cl.`location_type_id`,cp.`is_primary`,0 AS is_billing,cp.`mobile_provider_id`,cp.`phone`,NULL AS `phone_ext`,NULL AS `phone_type_id` 
FROM eworks_civicrm.civicrm_phone AS cp 
LEFT JOIN eworks_civicrm.civicrm_location AS cl ON cl.id = cp.location_id;

--  Adding Relationship of "Auditor"
INSERT INTO `gas_civicrm`.`civicrm_relationship`
(`contact_id_a`,`contact_id_b`,`relationship_type_id`,`start_date`,
`end_date`,`is_active`,`description`,`is_permission_a_b`,`is_permission_b_a`,`case_id`) 
SELECT cr.`contact_id_a`,cr.`contact_id_b`,10 AS `relationship_type_id`, cr.`start_date` ,
cr.`end_date`,cr.`is_active`,cr.`description`,0 AS `is_permission_a_b`,0 AS `is_permission_b_a`,NULL AS `case_id`
FROM eworks_civicrm.`civicrm_relationship` AS cr WHERE  cr.`relationship_type_id` = 8;

--  Adding Relationship of "Retrofit"
INSERT INTO `gas_civicrm`.`civicrm_relationship`
(`contact_id_a`,`contact_id_b`,`relationship_type_id`,`start_date`,
`end_date`,`is_active`,`description`,`is_permission_a_b`,`is_permission_b_a`,`case_id`) 
SELECT cr.`contact_id_a`,cr.`contact_id_b`,11 AS `relationship_type_id`, cr.`start_date` ,
cr.`end_date`,cr.`is_active`,cr.`description`,0 AS `is_permission_a_b`,0 AS `is_permission_b_a`,NULL AS `case_id`
FROM eworks_civicrm.`civicrm_relationship` AS cr WHERE  cr.`relationship_type_id` = 9;
 
--  Updating subscription-history in new DB
 INSERT INTO `gas_civicrm`.`civicrm_subscription_history`
(`id`,`contact_id`,`group_id`,`date`,`method`,`status`,`tracking`) 
SELECT * FROM eworks_civicrm.`civicrm_subscription_history`;

-- Insert first & last name fields from display_name field
-- UPDATE `gas_civicrm`.`civicrm_contact` SET `first_name` = SUBSTRING_INDEX(SUBSTRING_INDEX(display_name, ' ', 1), ' ', -1), last_name = SUBSTRING_INDEX(SUBSTRING_INDEX(display_name, ' ', 2), ' ', -1)
-- WHERE `contact_sub_type` LIKE '%Applicant%';

INSERT INTO `gas_drupal`.`users`(`uid`,`name`,`pass`,`mail`,`theme`,`signature`,`signature_format`,`created`,`access`,`login`,`status`,`timezone`,`language`,`picture`,`init`,`data`) 
SELECT `uid`,`name`,`pass`,`mail`,`theme`,`signature`,0,`created`,`access`,`login`,`status`,`timezone`,`language`,`picture`,`init`,NULL 
FROM eworks_drupal.users WHERE uid <> 0 and uid <> 1;

UPDATE `gas_drupal`.`users` SET `pass`='$S$Cd74UB1UtLGzDFePlPkSuAdlMhMJffMz9hJmI8y083TjtyzM9jfU'; 
 
-- Assigning role in new Drupal database  w.r.t contact sub type in old civi-database
--   Admin
--   Retrofit
--   superadmin
--   CSR
--   Auditor
 INSERT INTO `gas_drupal`.`users_roles` (`uid`,`rid`)
 SELECT u.uid,4 AS rid FROM eworks_civicrm.civicrm_contact AS cc 
 LEFT JOIN eworks_civicrm.civicrm_uf_match AS uf ON uf.contact_id = cc.id  
 LEFT JOIN `gas_drupal`.users AS u ON uf.uf_id = u.uid WHERE cc.contact_sub_type = 'Admin' AND u.uid != 'NULL';
  
 INSERT INTO `gas_drupal`.`users_roles` (`uid`,`rid`)
 SELECT u.uid,3 AS rid FROM eworks_civicrm.civicrm_contact AS cc 
 LEFT JOIN eworks_civicrm.civicrm_uf_match AS uf ON uf.contact_id = cc.id  
 LEFT JOIN `gas_drupal`.users AS u ON uf.uf_id = u.uid WHERE cc.contact_sub_type = 'superadmin'  AND u.uid != 'NULL';

 INSERT INTO `gas_drupal`.`users_roles` (`uid`,`rid`)
 SELECT u.uid,5 AS rid FROM eworks_civicrm.civicrm_contact AS cc 
 LEFT JOIN eworks_civicrm.civicrm_uf_match AS uf ON uf.contact_id = cc.id  
 LEFT JOIN `gas_drupal`.users AS u ON uf.uf_id = u.uid WHERE cc.contact_sub_type = 'CSR'  AND u.uid != 'NULL';

 INSERT INTO `gas_drupal`.`users_roles` (`uid`,`rid`)  
 SELECT u.uid,6 AS rid FROM eworks_civicrm.civicrm_contact AS cc 
 LEFT JOIN eworks_civicrm.civicrm_uf_match AS uf ON uf.contact_id = cc.id  
 LEFT JOIN `gas_drupal`.users AS u ON uf.uf_id = u.uid WHERE cc.contact_sub_type = 'Retrofit'  AND u.uid != 'NULL';

 INSERT INTO `gas_drupal`.`users_roles` (`uid`,`rid`)
 SELECT u.uid,7 AS rid FROM eworks_civicrm.civicrm_contact AS cc
 LEFT JOIN eworks_civicrm.civicrm_uf_match AS uf ON uf.contact_id = cc.id  
 LEFT JOIN `gas_drupal`.users AS u ON uf.uf_id = u.uid WHERE cc.contact_sub_type = 'Auditor'  AND u.uid != 'NULL';


INSERT INTO `gas_civicrm`.`civicrm_value_gcc_custom_group` ( `entity_id`, `region` ) 
SELECT entity_id, char_data FROM eworks_civicrm.civicrm_custom_value;


-- INSERT AUDITOR NOTES IN civicrm_notes table
INSERT INTO `gas_civicrm`.`civicrm_note`( entity_table, entity_id, note, contact_id, modified_date, subject, privacy ) 
SELECT 'gcc_applicant', ew.contact_id, ew.corrections, ew.contact_id, NULL AS modified_date, 'auditornotes', 0
FROM eworks_civicrm.gcc_applicant AS ew WHERE `corrections` != '0' AND `corrections` is not null AND `corrections` != '';

UPDATE `gas_civicrm`.`civicrm_contact` SET `first_name` = '', last_name = '' WHERE `contact_sub_type` LIKE '%Applicant%';

UPDATE `gas_civicrm`.`civicrm_contact` SET `first_name` = display_name WHERE `contact_sub_type` LIKE '%Applicant%';

-- UPDATE LANDLORD ADDRESS 
UPDATE `civicrm_address` as ca JOIN `civicrm_relationship` as cr ON ( ca.contact_id = cr.contact_id_b ) JOIN `civicrm_contact` as cc ON ( cr.contact_id_b = cc.id ) SET ca.contact_id = cr.contact_id_a , ca.location_type_id = 6 WHERE ca.location_type_id = 6 AND cr.relationship_type_id = 12;

-- UPDATE LANDLORD PHONE
UPDATE `civicrm_phone` as cp JOIN `civicrm_relationship` as cr ON ( cp.contact_id = cr.contact_id_b ) JOIN `civicrm_contact` as cc ON ( cr.contact_id_b = cc.id ) SET cp.contact_id = cr.contact_id_a , cp.location_type_id = 6 WHERE cp.location_type_id = 6 AND cr.relationship_type_id = 12;

-- Qureies to check whether there exits a record with blank landlord name but entries in address & phone table
-- SELECT ca . *
-- FROM `civicrm_address` ca
-- JOIN civicrm_location cl ON ( ca.location_id = cl.id )
-- WHERE cl.location_type_id =6
-- AND cl.name = ''
-- AND ca.street_address IS NOT NULL;

-- SELECT cp. *
-- FROM `civicrm_phone` cp
-- JOIN civicrm_location cl ON ( cp.location_id = cl.id )
-- WHERE cl.location_type_id =6
-- AND cl.name = '';

-- UPDATE civicrm_contact add  before and after contact_sub_type data
UPDATE `civicrm_contact` SET  `contact_sub_type` = "Applicant" WHERE `contact_sub_type` = 'Applicant';
UPDATE `civicrm_contact` SET  `contact_sub_type` = "Landlord" WHERE `contact_sub_type` = 'Landlord';
UPDATE `civicrm_contact` SET  `contact_sub_type` = "Admin" WHERE `contact_sub_type` = 'Admin';
UPDATE `civicrm_contact` SET  `contact_sub_type` = "Auditor" WHERE `contact_sub_type` = 'Auditor';
UPDATE `civicrm_contact` SET  `contact_sub_type` = "CSR" WHERE `contact_sub_type` = 'CSR';
UPDATE `civicrm_contact` SET  `contact_sub_type` = "Retrofit" WHERE `contact_sub_type` = 'Retrofit';

-- UPDATE civicrm_contact for contact_sub_type Landlord
UPDATE `civicrm_contact` SET `first_name` = display_name, sort_name = display_name WHERE `contact_sub_type` LIKE '%Landlord%' AND sort_name IS NULL AND first_name IS NULL;

-- UPDATE civicrm_address for contact_sub_type Landlord
UPDATE `civicrm_address` SET  `location_type_id` =  1 , `is_primary` = 1 WHERE `location_type_id` = 6 AND `is_primary` = 0;

