{if $action eq 2}
<div id = "AddLink" ><a href='javascript:AddRow()'><img src="{$config->resourceBase}i/TreePlus.gif" class="action-icon"/>{ts}Add More Files{/ts}</a>
 
{if $rows}
   <a href="{crmURL p='civicrm/efficiency/applicant/export' q="cid=`$cid`&reset=1&role=`$role`"}"><img src="{$config->resourceBase}i/TreePlus.gif" class="action-icon"/>Refresh Customer Info</a></div>
  {/if}
<div class="form-item">
     {$form.buttons._qf_Files_back.html}
     {$form.buttons._qf_Files_upload.html}
     {$form.buttons._qf_Files_cancel.html} 
      <table class="form-file">
       {foreach from=$form item=field key=keys}
      
     {if ($keys neq 'location') and ($field.label neq '') and ($field.label neq '<') and ($field.label neq 'L') and ($field.label neq 'F')}
     
	     <tr class={$keys}>
	       <td class="label">{$field.label}</td><td class="html-adjust">{$field.html}</td>
               
	    </tr>
	    	    
       
    {/if}
    {/foreach}
</table>
  {if $iselectric}
      <table>
	 <tr>
	    <td>{$form.H_S_checkDone.label}</td>
	    <td>{$form.H_S_checkDone.html}</td>
	 </tr>
      </table>
  {/if}




</div>


<br/><br/>

{else if $action eq 4}
  
{if $rows}
 {if $action neq 1 and $action neq 2 and $uploadApp_perm eq 0}
        <div id="id_addFiles_show"><a href="{crmURL p='civicrm/efficiency/applicant/files/update' q="cid=`$cid`&reset=1&action=update&selectedChild=files"}"><img src="{$config->resourceBase}i/TreePlus.gif" class="action-icon"/>{ts}Add More Files{/ts}</a></div>
  {/if}
  
  {if $uploadFAST} 
     <div class="action-link"><img src="{$config->resourceBase}i/TreePlus.gif" class="action-icon"/>{$uploadFAST}</div>
  {/if}

{if $refreshCustomer eq 1}
<div class="action-link"><a href="{crmURL p='civicrm/efficiency/applicant/export' q="cid=`$cid`&reset=1&role=`$role`"}" style="color:red !important"><img src="{$config->resourceBase}i/TreePlus.gif" class="action-icon"/>Refresh Customer Info</a></div>
{else}
  <div class="action-link"><a href="{crmURL p='civicrm/efficiency/applicant/export' q="cid=`$cid`&reset=1&role=`$role`"}"><img src="{$config->resourceBase}i/TreePlus.gif" class="action-icon"/>Refresh Customer Info</a></div>
{/if}

   <table cellpadding=0 cellspacing=1 border=1 width="90%" class="app">
     <tr><th class="grouplabel">File Name</th><th class="grouplabel">Description</th><th class="grouplabel">Date Uploaded</th><th></th></tr>
{assign var="count" value=1}
      {foreach from=$rows item=row}
              {if ($count is not even)}  
		       {assign var="class" value="odd-row"}
                       {else}
                       {assign var="class" value="even-row"}
                       {/if}
         <tr class='{$class}'><td><a href="{$row.fileURL}">{$row.name}</a></td><td>{$row.description}</td><td>{$row.date|truncate:10:''|crmDate}</td><td>{$row.deleteURL}</td></tr>
{assign var="count" value=$count+1}
     {/foreach}

   </table>

{else}
{if $uploadApp_perm eq 0}
<div class="Action-Link">
<a href="{crmURL p='civicrm/efficiency/applicant/files/update' q="reset=1&cid=`$cid`&action=update&selectedChild=files"}"><img src="{$config->resourceBase}i/TreePlus.gif" class="action-icon"/>{ts}Upload File{/ts}</a>
</div>

	<div class="view-content">

     <div class="messages status">
           <dl>
             <dt><img src="{$config->resourceBase}i/Inform.gif" alt="{ts}status{/ts}" /></dt>
               <dd>
                 {ts }There are no files uploaded, you can  <a href="{crmURL p='civicrm/efficiency/applicant/files/update' q="&cid=`$cid`&reset=1&action=update&selectedChild=files"}">{ts}upload one now{/ts}</a>.{/ts}
               </dd>
           </dl>
       </div> 
</div>
{else}
	<div class="view-content">

     <div class="messages status">
           <dl>
             <dt><img src="{$config->resourceBase}i/Inform.gif" alt="{ts}status{/ts}" /></dt>
               <dd>
                 {ts }There are no files.{/ts}
               </dd>
           </dl>
       </div> 
</div>
	
{/if}

{/if}

{/if}

{* File Listing Start*}
{if $action eq 2}
   {if $rows}
      <table cellpadding=0 cellspacing=1 border=1 width="90%" class="app">
      	<tr>
	  <th class="grouplabel">File Name</th><th class="grouplabel">Description</th>
	  <th class="grouplabel">Date Uploaded</th>
	  <th></th>
	</tr>
      {foreach from=$rows item=row}
        <tr>
	  <td><a href="{$row.fileURL}">{$row.name}</a></td>
          <td>{$row.description}</td><td>{$row.date|truncate:10:''|crmDate}</td>
	  <td>{$row.deleteURL}</td>
        </tr>
      {/foreach}
      </table>
   {if $uploadFast}
      <br/>   
      <div class="action-link">
         <a href="{crmURL p='civicrm/efficiency/applicant/import' q="cid=`$cid`"}">&nbsp;&raquo;Upload {$fat_fast}</a>
      </div>
      <br/>
   {/if}
  
 
   {/if}
{/if}
{* File Listing End*}

{literal}

<script>
 cj('document').ready(function(){

        cj('.form-file').find("tr:gt(2)").hide();
        cj('.attributes').remove();
});

function AddRow(){

var rows = cj('.form-file tr:visible').length;
  if(rows == 8){
       cj('#AddLink').hide();
      }

rows = rows+2;
rows = rows/2;
cj('.uploadFile-'+rows).removeAttr('style');
cj('.description-'+rows).removeAttr('style');

    }
    
</script>

{/literal}