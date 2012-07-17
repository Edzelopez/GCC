{*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.1                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2011                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*}
{* Default template custom searches. This template is used automatically if templateFile() function not defined in
   custom search .php file. If you want a different layout, clone and customize this file and point to new file using
   templateFile() function.*}
<div class="crm-block crm-form-block crm-contact-custom-search-form-block">
<div class="crm-accordion-wrapper crm-custom_search_form-accordion crm-accordion-open">
    {*<div class="crm-customsearch-header crm-master-accordion-header">*}
      {*<div class="icon crm-accordion-pointer"></div>*}
      {*{ts}Edit Search Criteria{/ts}*}
    {*</div>*}<!-- /.crm-accordion-header -->
    <div class="crm-customsearch-body">
        {*<div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="top"}</div>*}
        <table class="form-layout-compressed">
            <tr class="crm-contact-custom-search-form-row-{$element}">
                    <td>{$form.file_identifier.label}</td>
                    <td>{$form.file_identifier.html}</td>
		    <td class="td-sbutton">{include file="CRM/common/formButtons.tpl" location="bottom"}</td>
            </tr>
        </table>

	<table class="list-participants-status">
            <tr>
                <td>Show Records</td>
		<td>
		   <table>
		      <tr>
		          {if $electric}
			     <td>{$form.status_applicant.html}</td>
                          {/if}
			  <td>{$form.status_new_participant.html}</td>
			  <td>{$form.status_audit_assigned.html}</td>
			  <td>{$form.status_retrofit_pending.html}</td>
			  <td>{$form.status_no_potential.html}</td>
		      </tr>
		      <tr>
			  <td>{$form.status_close_participant_withdraw.html}</td>
			  <td>{$form.status_retrofit_completed.html}</td>
			  {if $electric}			     
			     <td>{$form.status_ready_for_QA.html}</td>
			     <td>{$form.status_report_to_LDC.html}</td>	
			     <td>{$form.status_project_completed.html}</td>
                          {else} 
                             <td colspan=2>{$form.status_project_completed.html}</td>
                          {/if}
		      </tr>
		   </table>
		</td>
                {* Loop through all defined search criteria fields (defined in the buildForm() function except file id). *}
                <!--{foreach from=$elements item=element}
	           {if $element neq 'file_identifier'} 
                       <td>{$form.$element.html}</td>
                   {/if}
                {/foreach}-->
	    </tr>
        </table>
        {*<div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="bottom"}</div>*}
    </div><!-- /.crm-accordion-body -->
</div><!-- /.crm-accordion-wrapper -->
</div><!-- /.crm-form-block -->

{if $rowsEmpty || $rows}
<div class="crm-content-block">
{if $rowsEmpty}
    {include file="CRM/Contact/Form/Search/Custom/EmptyResults.tpl"}
{/if}

{if $summary}
    {$summary.summary}: {$summary.total}
{/if}

{if $rows}
	<div class="crm-results-block">
    {* Search request has returned 1 or more matching rows. Display results and collapse the search criteria fieldset. *}
        {* This section handles form elements for action task select and submit *}
       <div class="crm-search-tasks">        
        {include file="CRM/Contact/Form/Search/ResultTasks.tpl"}
		</div>
        {* This section displays the rows along and includes the paging controls *}
	    <div class="crm-search-results">

        {include file="CRM/common/pager.tpl" location="top"}

        {* Include alpha pager if defined. *}
        {if $atoZ}
            {include file="CRM/common/pagerAToZ.tpl"}
        {/if}
        
        {strip}
        <table class="selector" summary="{ts}Search results listings.{/ts}">
            <thead class="sticky">
                <tr>
		{*Commeted Select All Rows checkboxes since no actions*}
                {*<th scope="col" title="Select All Rows">{$form.toggleSelect.html}</th>*}
                {foreach from=$columnHeaders item=header}
		   {if $header.name neq 'ProjectDetails Status'}
                    <th scope="col">
                        {if $header.sort}
                            {assign var='key' value=$header.sort}
                            {$sort->_response.$key.link}
                        {else}
                            {$header.name}
                        {/if}
                    </th>
                   {/if}
                {/foreach}
                <th>&nbsp;</th>
                </tr>
            </thead>

            {counter start=0 skip=1 print=false}
            {foreach from=$rows item=row}
                <tr id='rowid{$row.contact_id}' class="{cycle values="odd-row,even-row"}">
                    {assign var=cbName value=$row.checkbox}
		    {*Commeted Select All Rows checkboxes since no actions*}
                    {*<td>{$form.$cbName.html}</td>*}
                    {foreach from=$columnHeaders item=header}
                        {assign var=fName value=$header.sort}
                        {if $fName neq 'Status'}
			    {if $fName eq 'sort_name'}
                                <td><a href="{crmURL p='civicrm/contact/view' q="reset=1&cid=`$row.contact_id`"}">{$row.sort_name}</a></td>
			    {elseif $fName eq 'date_entered'}
			        <td>{$row.$fName|crmDate}</td>
			    {else}
			       <td>{$row.$fName}</td>
                            {/if}
			{/if}
                    {/foreach}
		    {if $row.action}
                    	<td>{$row.action}</td>
	            {/if}
                </tr>
            {/foreach}
        </table>
        {/strip}

        <script type="text/javascript">
        {* this function is called to change the color of selected row(s) *}
        var fname = "{$form.formName}";	
        on_load_init_checkboxes(fname);
        </script>

        {include file="CRM/common/pager.tpl" location="bottom"}

        </p>
    {* END Actions/Results section *}
    </div>
    </div>
{/if}



</div>
{/if}
{literal}
<script type="text/javascript">
cj(function() {
   cj().crmaccordions(); 
});

cj('span.crm-psearch').parent().addClass('search-red');
cj('span.crm-center').parent().addClass('crm-center');
	
cj('div.crm-search-tasks').hide();
cj('span.crm-button').removeClass().addClass('cs-button');
</script>
{/literal}