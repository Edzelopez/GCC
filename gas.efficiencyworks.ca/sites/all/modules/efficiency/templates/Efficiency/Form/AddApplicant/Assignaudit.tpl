{if $iselectric neq 1}
<div id="help">Assign Audit when scheduled</div>
{/if}
{if $rows}
   {if $action eq 4 and $editAssign eq 1}
     <div id="edit-Audit" class="" title="{ts}Edit Audit{/ts}">
       <a href="{crmURL p='civicrm/efficiency/applicant/assignaudit/update' q="cid=`$cid`&reset=1&action=update"}">&nbsp;&raquo;{ts}Edit to Assign{/ts}</a>	    	        
    </div>
   {/if}
  <table>
    <tr class="{cycle values="odd-row,even-row"}">
            <td class="label">Auditor</td>
            <td>{$rows.Auditor.name}</td>
    </tr>
    <tr class="{cycle values="odd-row,even-row"}">
            <td class="label">Retrofit Manager</td>
            <td>{$rows.Retrofit.name}</td>
    </tr>
   </table>       
    {if $action eq 4 AND $iselectric}
      {if $action eq 4 and $editAssign eq 1}
         <div id="edit-Audit" class="" title="{ts}Edit Audit{/ts}">
             <a href="{crmURL p='civicrm/efficiency/applicant/assignaudit/update' q="cid=`$cid`&reset=1&action=update"}">&nbsp;&raquo;{ts}Edit QA{/ts}</a>	    	        
         </div>
      {/if}
      <table>
        <tr class="{cycle values="odd-row,even-row"}">
           <td class="label">Verification Auditor</td>
           <td>{$Auditor2QA}</td>
        </tr>
        <tr class="{cycle values="odd-row,even-row"}">
           <td class="label">QA Phone Call</td>
           <td>{$QAPhoneCall|truncate:10:''|crmDate}</td>
        </tr>
        <tr class="{cycle values="odd-row,even-row"}">
           <td class="label">QA Notes</td>  
           <td>{$QANotes}</td>
        </tr>
      </table> 
   {/if}
  
{elseif $action neq 1 and $action neq 2 }
    <div class="messages status">
           <dl>
             <dt><img src="{$config->resourceBase}i/Inform.gif" alt="{ts}status{/ts}" /></dt>
               <dd>
                 {ts }There is no ConfirmAudit information , you can  <a href="{crmURL p='civicrm/efficiency/applicant/assignaudit/update' q="cid=`$cid`&reset=1&action=update"}">{ts}add here{/ts}</a>.{/ts}
               </dd>
           </dl>
   </div>
   {if $iselectric}
          <table >
             <tr class="odd-row">
              <td class="label" >QA Phone Call</td>
              <td>{$QAPhoneCall|truncate:10:''|crmDate}</td>
             </tr>
            <tr class="even-row">
             <td>QA Notes</td>  
             <td>{$QANotes}</td>
            </tr>
          </table><br />
  {/if}
{/if}
 

{if $action eq 2 or $action eq 1}
<fieldset><legend>{ts}Edit Relationship{/ts}</legend>
<div class="form-item"> 
    <dl>
        <dt>{$form.auditor_id.label}</dt><dd>{$form.auditor_id.html}</dd>
        <dt>{$form.retrofit_mgr_id.label}</dt><dd>{$form.retrofit_mgr_id.html}</dd>
        {if $action eq 2 or $action eq 1 or !$context}
            <dd>{$form.buttons._qf_Assignaudit_submit.html}  
            {$form.buttons._qf_Assignaudit_cancel.html}
            </dd>
        {/if}
	{if $iselectric eq 1}
	<dt>{$form.Auditor2QA.label}</dt><dd>{$form.Auditor2QA.html}</dd>
        {if $form.QA_phone_Call.label}
	<dt>{$form.QA_phone_Call.label}</dt><dd><span class="value">{include file="CRM/common/jcalendar.tpl" elementName=QA_phone_Call}</span></dd>	
        {/if}
	<dt>{$form.QANoteValues.label}</dt><dd>{$form.QANoteValues.html}</dd>
    	<dd>{$form.buttons._qf_Assignaudit_submit_saveqa.html}
            {$form.buttons._qf_Assignaudit_cancel.html}
        </dd>
	{/if}
     </dl>
      
</div>
</fieldset>
{/if}
<br/>
{*
{if $action eq 2 or $action eq 1 or !$context}
  {$form.buttons._qf_Assignaudit_submit.html}  
  {$form.buttons._qf_Assignaudit_cancel.html}
{/if}
*}
{if $form.assign_audit_status and $iselectric eq 1 }
  <div id='Status'>
    <table id='StatusTable' cellpadding=0 cellspacing=1 border=1 width="90%">
      <tr>
  	<th>{ts}Status{/ts}</th>
      </tr>
      <tr>
	<td>{$form.assign_audit_status.label}{$form.assign_audit_status.html}{$clearStatus}</td>
      </tr>
      <tr>
	<td id='setStatusButton' class="grouplabel"2>
	     {$form.buttons._qf_Assignaudit_upload.html}
       	</td>
      </tr>
    </table>
  </div>
{/if}

{literal}
  <script>
 cj('document').ready( function () {
      cj('#clearstatus').click( function() {
         cj('#StatusTable input:radio').attr('checked', false);
        });
     });
  </script>
{/literal}