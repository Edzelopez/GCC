{if $action eq 2}
<div class="customdata-block">
{foreach from=$groupTree item=cd_edit key=group_id name=custom_sets}    
      
            <table class="custom-data-form">
                {foreach from=$cd_edit.fields item=element key=field_id}
		   {if $element.label neq "Auditor's Notes" and $element.label neq "Landlord Audit Consent" and $element.label neq "Auto Status"}
		       {include file="CRM/Custom/Form/CustomField.tpl"}
	           {/if}
		   {if $element.label eq "Landlord Audit Consent"}
                      <tr>
		         <td class="label">{$element.label}</td>
		         <td class="html-adjust"><a href="{$addFilesUrl}">Upload<a></td>
                      </tr>
		   {/if}
		   {if $actionString eq 'update' and $element.label eq 'Enrolled'}
		   <td colspan="2" class="message"><span class="description-enrolled font-italic">"May only be edited if client requests a rescheduling"</span></td>
		   {/if}
                {/foreach}
            </table>
	    <div class="spacer"></div>
{/foreach}
</div>


<tr>

    <td class="grouplabel" colspan=2 >
        {$form.buttons._qf_Details_upload.html}
    	{$form.buttons._qf_Details_submit.html}
       	{$form.buttons._qf_Details_cancel.html}
    </td>

</tr>
{else if $action eq 4}

{if $editUrl}
    <div id="edit-application" class="" title="{ts}Edit Application{/ts}">
    	 <a href="{$editUrl}" title="{ts}Edit Application{/ts}">&nbsp;{ts}>>Edit Application{/ts}</a>     
    </div><!-- End of Edit div--> 
{/if}
 {assign var="count" value=1}
{foreach from=$viewCustomData item=customValues key=customGroupId}
        {foreach from=$customValues item=cd_edit key=cvID}
            <div class="customFieldGroup">
                <table id="{$cd_edit.name}" >
                  {foreach from=$cd_edit.fields item=element key=field_id}
                     {if ($count is not even)}
		       {assign var="class" value="odd-row"}
                       {else}
                       {assign var="class" value="even-row"}
                      {/if}
		       {if $element.field_title neq 'QA Status' and $element.field_title neq 'Status' and $element.field_title neq 'Auto Status' and $element.field_title neq "Auditor's Notes" and $element.field_title neq "Landlord Audit Consent" and $element.field_title neq 'HAP FileID'}
		  	   <tr class= "fields {$class}">
			     {if $element.options_per_line != 0}
      			       <td class="label">{$element.field_title}</td>
      			       <td class="crm-custom_data">
          		         {* sort by fails for option per line. Added a variable to iterate through the element array*}
          		           {foreach from=$element.field_value item=val}
              		   	       {$val}
          		           {/foreach}
                               </td>
                             {else}
			       
                               <td class="label">{$element.field_title}</td>
                               {if $element.field_type == 'File'}
                                   {if $element.field_value.displayURL}
                                       <td class="crm-custom_data crm-displayURL"><a href="javascript:imagePopUp('{$element.field_value.imageURL}')" ><img src="{$element.field_value.displayURL}" height = "{$element.field_value.imageThumbHeight}" width="{$element.field_value.imageThumbWidth}"></a></td>
                                   {else}
                                       <td class="html-adjust crm-custom_data crm-fileURL"><a href="{$element.field_value.fileURL}">{$element.field_value.fileName}</a></td>
                                   {/if}
                               {else}
                                   <td class="html-adjust crm-custom-data">{$element.field_value}</td>
                               {/if}
                              {/if}
                             </tr>
		        {/if}
                {assign var="count" value=$count+1}
                  {/foreach}
                </table>
            </div>
        {/foreach}
{/foreach}


{/if}

{literal}
  <script>
  cj(document).ready(function(){

    var actionString = "{/literal}{$actionString}{literal}";

    if ( actionString == 'add') {
      cj('#_qf_Details_cancel').click( function() {
         var redirect = '';
         redirect = "{/literal}{$cancelAddApplicantUrl}{literal}";

         var response = confirm("Are you sure you want to cancel adding Applicant and related details ?");
         if ( response == true ) {
            window.location=redirect;
            return false;
         } else {
            return false;
         }
      });
     } 
 
  });

  cj('#_qf_Details_upload').css( 'float', 'left' );

  </script>
{/literal}