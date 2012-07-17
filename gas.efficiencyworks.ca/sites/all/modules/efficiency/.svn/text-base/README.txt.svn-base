
CONTENTS OF THIS FILE
---------------------

 * Setup for Efficiency Works
 * Drupal Configuration
 * Drupal Theme Configuration
 * Civicrm Configuration
 * Permission Setup as per Role
 * Workflow as per Role


SETUP FOR EFFICIENCY WORKS
--------------------------

Follow below steps to upgrade and migrate from -
GCC-drupalv5.0
GCC-civicrm1.9
TO-
GCC-drupalv7.0
GCC-civicrm4.0

1) Install new Drupal7.0 and Civicrm-v4.0 stable version

2) Run xml script through URL as below mentioned
http:{your site base URL}sites/all/modules/civicrm/bin/migrate/import.php?name={your drupal username}&pass={Drupal user's resp password}&key={your civicrm site key from civicrm.settings.php file}&file={path of xml file}GasCustomSet.xml

Note: replace GasCustomSet.xml with ElectricityCustomSet.xml for electric version

3)Run xml script through URL as below mentioned
http:{your site base URL}sites/all/modules/civicrm/bin/migrate/import.php?name={your drupal username}&pass={Drupal user's resp password}&key={your civicrm site key from civicrm.settings.php file}&file={path of xml file}CommonCustomSet.xml

Note: Run same #3 url for electric version as well

4)Run xml script through URL as below mentioned
http:{your site base URL}sites/all/modules/civicrm/bin/migrate/import.php?name={your drupal username}&pass={Drupal user's resp password}&key={your civicrm site key from civicrm.settings.php file}&file={path of xml file}CommonCustomProfile.xml

4) Open gasData.sql(For Gas version) file under efficiency/sql folder
 Replace-gas_drupal with your new drupal database name same with gas_civicrm.

5)Import gasData.sql(For Gas version) file in your mysql database.

6)Now go through site and enable module (Efficiency work)

7)Rebuild Civicrm menu through URl as mentioned below-
  {your site base URL}/civicrm/menu/rebuild?reset=1

8)To check with Administrator for adding any applicant. Add the respective civicrm contact in any group and then try adding applicant.




DRIPAL CONFIGURATION
--------------------

1)Create a new Content of Basic page type with -
 title : "Efficiency Works" and
 body  : This is a private site

 -- Click on URL Path settings and set URL alias as "home"

Note: Both for Gas and Electric version

2)Disable Navigation block on left side having Civicrm as link

Note: Both for Gas and Electric version

3)Download and configure modules by following below steps-

 -Through terminal window get at {your drupalcodebasefolder}/sites/all/modules
 -Run -            "wget http://ftp.drupal.org/files/projects/login_destination-7.x-1.0.tar.gz"
                   "wget http://ftp.drupal.org/files/projects/front-7.x-2.1.tar.gz"
		   "wget http://ftp.drupal.org/files/projects/flood_control-7.x-1.0.tar.gz"

 -Extract folder - "tar -xzvf login_destination-7.x-1.0.tar.gz"
                   "tar -xzvf front-7.x-2.1.tar.gz"
		   "tar -xzvf flood_control-7.x-1.0.tar.gz"

 -Enable module (Login Destination) from your site.

  -- Click on "Configure" link of Login Destination module then on "Add login destination rule"
  -- Add url "civicrm/contact/search/custom?csid=16&reset=1&force=1" in the textarea.
  -- Check "Login, registration, one-time login link " under Redirect upon triggers link.Check all Roles options below that and click on save.

 -Enable module (Front Page) from your site.
  
 -- Click on "Configure" link of Front Page module.
 -- Check the checkbox "Front Page Override " at the top
 -- Foreach Role except Anonymous Select Mode "Redirect" and set Path to "civicrm/contact/search/custom?csid=16&reset=1&force=1"
 -- For Anonymous Role Select Mode "Redirect" and set Path to "home"

 -Enable module (Flood control) from your site.
 
 -- Click on "Configure" link of Flood control
 -- Set the Failed login (IP) limit as per your need 
 -- Set the Failed login (IP) window as per your need
 -- Set the Failed login (username) limit as per your need
 -- Set the Failed login (username) window as per your need 
 -- Save configuration

Note:Both for Gas and Electric version




DRUPAL THEME CONFIGURATION
--------------------------

1) Create "themes" folder under {your drupalcodebasefolder}/sites/{multisite gas folder}/

2) Copy "garland" theme folder from your {your drupalcodebasefolder}/themes and Paste it to {your drupalcodebasefolder}/sites/{multisite gas folder}/themes.

3) Add images from svn directory/themes/gas/garland/(images) and (files) folder to {yourdrupalcodebase folder}/sites{multisite folder}/themes/garland/(images) and (files) resp.

4) Go to the "Appearance" Tab at the top menu of Drupal. Under the "Disabled theme" section, click on the "Enable and set default" for the Garland theme.

5) After setting "Garland Theme" as default. Click on the "Garland theme" settings  and UnCheck the "Main menu", "Secondary menu" options  under the "Toggle display Section".

6) Under the "Logo image settings" section, UnCheck the "Use the default Logo" Check-box and upload Image "gas_2.png" from the path {your drupalcodebasefolder}/sites/{multisite folder}/themes/garland/files/gas_2.png by clicking on the Browse button and save it by Clicking on the save Configuration button.

7) Go to the "Structure" Tab at the top menu of Drupal. Click on the "Blocks" link and select the Region as "None" for the dropdown  for "Search form" Block.

8) Click on the "configure" link of "Efficiency Navigation" Block. Under the "Visibility settings" section Click on "Roles" and Check the option "authenticated user" , save it by clicking on the Save block button.

9)Click on "Configuration" in top menu of Drupal and Under that "Performance" link to "Clear Cache".




CIVICRM CONFIGURATION
---------------------

1) Set Date to DD-MMM-YYYY format-Follow below steps
 - Navigate through url:{your site base URL}/civicrm/admin/setting/date?reset=1

 - Set Complete Date and Time - %d-%b-%Y
 - Set Complete Date          - %d-%b-%Y

Note:Both for Gas and Electric version

2) Set Default country to -Canada

 - Navigate through url:{your site base URL}/civicrm/admin/setting/localization?reset=1
 - Set Default Country to "Canada" from Default Country select box.
 - Add country "Canada" from Available country 
 - Add country "Canada" from Available States and Provinces  	




PERMISSION SETUP AS PER ROLE
----------------------------

AUTHENTICATED USER

-Search and List Participants
-View Contact Info Tab 
-View Landlord Tab 
-View Household Info Tab
-View CSR Note
-Edit CSR Note 
-View Files Tab 
-View Project Details Tab 


ADMINISTEOR

-Add Participant 
-Search and List Participants
-Reports Link
-List Option Groups Block Link 
-Update FAT/FAST Block Link 
-View Contact Info Tab 
-Edit Contact Info Tab 
-View Landlord Tab 
-Edit Landlord Tab
-View Household Info Tab
-Edit Household Info Tab 
-View CSR Note
-Edit CSR Note 
-View Files Tab 
-Upload Files
-Delete Files 
-Upload Applicant FAT/FAST 
-View Assign Audit Tab
-Edit Assign Audit 
-View Project Details Tab 
-Edit Project Details Tab
-View Set Status Block 
-Edit Set Status Block 
-Edit Project Details Dates
-Edit Retrofit Completed 
-Edit Basic Gas M3 
-Edit Audit Invoiced 
-Edit Retrofit Invoiced 
-View Project Invoiced
-Access old GC-Audit Report 
-Access old GC-Retrofit Report 
-Access Enbridge report 
-Administer Option Groups 
-Administer QA Status


ADMIN

-Add Participant 
-Search and List Participants
-Reports Link
-View Contact Info Tab 
-Edit Contact Info Tab 
-View Landlord Tab 
-Edit Landlord Tab
-View Household Info Tab
-Edit Household Info Tab 
-View CSR Note
-Edit CSR Note 
-View Files Tab 
-Upload Files
-Upload Applicant FAT/FAST 
-View Assign Audit Tab
-Edit Assign Audit 
-View Project Details Tab 
-Edit Project Details Tab
-Edit Basic Gas M3 
-Edit Audit Invoiced 
-Edit Retrofit Invoiced 
-Administer Option Groups 


CSR

-Add Participant 
-Search and List Participants
-Reports Link
-View Contact Info Tab 
-Edit Contact Info Tab 
-View Landlord Tab 
-Edit Landlord Tab
-View Household Info Tab
-Edit Household Info Tab 
-View CSR Note
-Edit CSR Note 
-View Files Tab 
-View Assign Audit Tab
-Edit Assign Audit 
-View Project Details Tab 
-Edit Project Details Dates
-Edit Basic Gas M3 
-Access old GC-Audit Report 
-Access old GC-Retrofit Report 


RETROFIT

-Search and List Participants
-Reports Link
-View Contact Info Tab 
-View Landlord Tab 
-View Household Info Tab
-View CSR Note
-Edit CSR Note 
-View Files Tab 
-Upload Files
-Upload Applicant FAT/FAST 
-View Project Details Tab 
-Edit Project Details Tab
-View Set Status Block 
-Edit Set Status Block 
-Edit Project Details Dates
-Edit Retrofit Completed 


AUDITOR

-Search and List Participants
-Reports Link
-View Contact Info Tab 
-View Landlord Tab 
-View Household Info Tab
-View CSR Note
-Edit CSR Note 
-View Files Tab 
-Upload Files
-Upload Applicant FAT/FAST 
-View Project Details Tab 
-Edit Project Details Tab
-View Set Status Block 
-Edit Set Status Block 
-Edit Project Details Dates
-Edit Retrofit Completed 




WORKFLOW AS PER ROLE
--------------------

1)User with CSR role creates the applicant and assign for Audit/Retrofit.

2)User with CSR also can change the Project dates once the FAT/FAST file is uploaded by either Auditor/Admin/SuperAdmin/Retrofit which changes the Project details of the resp. participant for which it is uploaded.

3)Once the file is being uploaded for the participant it is listed under "List Participant" with "Review" as QA Status which once clicked by Superadmin will be set to OK.

4)User with Auditor/CSR role is only able to see the participant which are being assigned to him/her.

5)Auditor can view the detail of applicant and will be allowed to edit only note related to that.

6)Auditor can also change the Retrofit date.

7)Retrofit can also change the details of applicant,retrofit completed date and project status/dates.

8)Admin can check with all participants and can update details of that respective participant.

9)Admin is also allowed to change the audit and retrofit invoice data.
  
10)Admin can also change the option groups list which needs to displayed under applicant form.

11)While adding participant File ID should be like 'ABCD-00001' format, first four capital letters followed by hyphen and then five numbers (i.e. total 11 Capital aplhanumeric characters).






