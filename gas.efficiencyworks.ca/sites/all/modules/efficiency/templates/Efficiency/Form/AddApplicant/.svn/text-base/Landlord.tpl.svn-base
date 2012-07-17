{if $action eq 2}
<table cellpadding=0 cellspacing=1 border=1 width="90%" class="app">
       
  {if $iselectric}
      
     {foreach from=$groupTree item=cd_edit key=group_id name=custom_sets}    
                {foreach from=$cd_edit.fields item=element key=field_id}
		{if $element.label eq 'Social Housing' or $element.label eq '#of unitsSH' or $element.label eq 'SH Blanket Eligibility' or $element.label eq 'Corporate Name'}
                   {include file="CRM/Custom/Form/CustomField.tpl"}
		   {/if}
                {/foreach}
    {/foreach}
    {foreach from=$form item=field key=keys}
      
     {if ($keys neq 'location') and ($field.label neq '') and ($field.label neq '<') and ($field.label neq 'L')}
     
	     {if $keys eq 'first_name' or $keys eq 'email' or $keys eq 'last_name'}<tr>
	       <td class="label">{$field.label}</td>
               <td class="fieldlabel">{$field.html}</td></tr>
	    {/if}	    
       
    {/if}

    {if ($keys eq 'location')}
      {foreach from=$field item=one}
        {foreach from=$one item=address key=add1 }
             
         {if ($add1 eq 'address') } 
           {foreach from=$address item=addressItem key=addressItemKey}
            <tr>
	       {if $addressItemKey neq 'country_id'}
                 <td class="label">{$addressItem.label}</td>
                 <td class="fieldlabel">{$addressItem.html}</td>
	       {/if}
           </tr>
          {/foreach}
         {/if}

         {if ($add1 eq 'phone') } 
           {foreach from=$address item=addressItem }
             {foreach from=$addressItem item=addressItem1 }
               <tr>
                  <td class="label">Cell#</td>
                  <td class="fieldlabel">{$addressItem1.html}</td>
              </tr>
            {/foreach}
          {/foreach}
         {/if}


       {/foreach}
     {/foreach}
   {/if}


  {/foreach}
 {foreach from=$groupTree item=cd_edit key=group_id name=custom_sets}    
           
                {foreach from=$cd_edit.fields item=element key=field_id}
		{if $element.label eq 'LL pays heat bill' or $element.label eq 'LL pays power bill' or $element.label eq 'Heat metering' or $element.label eq 'Electricity metering'}
                   {include file="CRM/Custom/Form/CustomField.tpl"}
		   {/if}
                {/foreach}
           
	    <div class="spacer"></div>
{/foreach}
  {else}

    {foreach from=$form item=field key=keys}
     {if ($keys neq 'location') and ($field.label neq '') and ($field.label neq '<') and ($field.label neq 'L')}
        <tr>
            <td class="label">{$field.label}</td>
            <td class="fieldlabel">{$field.html}</td>
        </tr>
    {/if}

    {if ($keys eq 'location')}
      {foreach from=$field item=one}
        {foreach from=$one item=address key=add1 }
             
         {if ($add1 eq 'address') } 
           {foreach from=$address item=addressItem }
            <tr>
               <td class="label">{$addressItem.label}</td>
               <td class="fieldlabel">{$addressItem.html}</td>
           </tr>
          {/foreach}
         {/if}

         {if ($add1 eq 'phone') } 
           {foreach from=$address item=addressItem }
             {foreach from=$addressItem item=addressItem1 }
               <tr>
                  <td class="label">{$addressItem1.label}</td>
                  <td class="fieldlabel">{$addressItem1.html}</td>
              </tr>
            {/foreach}
          {/foreach}
         {/if}


       {/foreach}
     {/foreach}
   {/if}


  {/foreach}
 {/if}
<tr>

    <td class="grouplabel" colspan=2 >
        {$form.buttons._qf_Landlord_upload.html}
	    {$form.buttons._qf_Landlord_submit.html}
        {$form.buttons._qf_Landlord_cancel.html}        
    </td>

</tr>

</table>

{else if $action eq 4}
		
		{if $editUrl}
	    	    <div id="edit-landlord" class="" title="{ts}Edit Landlord{/ts}">
	    	    	 <a href="{$editUrl}" title="{ts}Edit Landlord{/ts}">&nbsp;{ts}>>Edit Landlord{/ts}</a>     
	            </div><!-- End of Edit div--> 
        	{/if}

 {assign var="count" value=1}
      {if $iselectric}
        <div title="Landlord Info" id="contact-landlord" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
         <table>
		{foreach from=$viewCustomData item=customValues key=customGroupId}
                 {foreach from=$customValues item=cd_edit key=cvID}
                   {foreach from=$cd_edit.fields item=element key=field_id}
                      {if ($count is not even)}
		       {assign var="class" value="odd-row"}
                       {else}
                       {assign var="class" value="even-row"}
                      {/if}
		  	   <tr class= "fields {$class}">
			     {if $element.options_per_line != 0}
			       {if $element.field_title eq 'Social Housing' or $element.field_title eq '#of unitsSH' or $element.field_title eq 'SH Blanket Eligibility' or $element.field_title eq 'Corporate Name'}
      			       <td class="label">{$element.field_title}</td>
      			       <td class="crm-custom_data">
          		         {* sort by fails for option per line. Added a variable to iterate through the element array*}
          		           {foreach from=$element.field_value item=val}
              		   	       {$val}
          		           {/foreach}
                               </td>
			       {/if}
			      {else}
				{if $element.field_title eq 'Social Housing' or $element.field_title eq '#of unitsSH' or $element.field_title eq 'SH Blanket Eligibility' or $element.field_title eq 'Corporate Name' }
                               <td class="label">{$element.field_title}</td>
                               {if $element.field_type == 'File'}
                                   {if $element.field_value.displayURL}
                                       <td class="crm-custom_data crm-displayURL"><a href="javascript:imagePopUp('{$element.field_value.imageURL}')" ><img src="{$element.field_value.displayURL}" height = "{$element.field_value.imageThumbHeight}" width="{$element.field_value.imageThumbWidth}"></a></td>
                                   {else}
                                       <td class="html-adjust crm-custom_data crm-fileURL"><a href="{$element.field_value.fileURL}">{$element.field_value.fileName}</a></td>
                                   {/if}
                               {elseif $element.field_data_type EQ 'ContactReference' && $element.contact_ref_id}
                                   {*Contact ref id passed if user has sufficient permissions - so make a link.*}
                                   <td class="html-adjust crm-custom-data crm-contact-reference">
                                      <a href="{crmURL p='civicrm/contact/view' q="reset=1&cid=`$element.contact_ref_id`"}" title="View contact">{$element.field_value}</a>
                                   </td>
                               {else}
                                   <td class="html-adjust crm-custom-data">{$element.field_value}</td>
				{/if}   
                               {/if}
                              {/if}
                             </tr> 
 {assign var="count" value=$count+1}
                  {/foreach}
        {/foreach}
{/foreach}

	             {if $contactinfo }
	    
	        
			 <tr class= "odd-row">
				<td class='label'>Contact Name</td>
				<td>{$contactinfo.first_name}</td>
			 </tr>
			 {*
			 <tr class= "even-row">
				<td class='label'>Last Name</td>
				<td>{$contactinfo.last_name}</td>
			 </tr>
			 *}
			 <tr class= "even-row">
				<td class='label'>Email</td>
				<td>{$contactinfo.email}</td>
			 </tr>
			 <tr class= "odd-row" >
				<td class='label'>Permanent Address</td>
				<td>{$contactinfo.permanant_address}</td>
			 </tr>

			 {if $iselectric neq 1}
			 <tr class= "odd-row">
				<td class='label'>Additional Address 1</td>
			        <td>{$contactinfo.additional_add_1}</td>
			 </tr>
			 <tr class= "even-row">
				<td class='label'>Additional Address 2</td>
				<td>{$contactinfo.additional_add_2}</td>
			 </tr>
			 {/if}
			 <tr class= "even-row">
				<td class='label'>City</td>
				<td>{$contactinfo.city}</td>
			 </tr>
			 <tr class= "odd-row">
				<td class='label'>Postal Code</td>
				<td>{$contactinfo.zip_postal_code}</td>
			 </tr>
		
			 <tr class= "even-row" >
				<td class='label'>Province</td>
				<td>{$contactinfo.state_province}</td>
			 </tr>
			 <tr class= "odd-row">
				<td class='label'>Cell#</td>
				<td>{$contactinfo.permanant_telephone}</td>
			 </tr>
		     {/if}


{assign var="count" value=1}
	{foreach from=$viewCustomData item=customValues key=customGroupId}
                 {foreach from=$customValues item=cd_edit key=cvID}
                   {foreach from=$cd_edit.fields item=element key=field_id}
                       {if ($count is not even)}  
		       {assign var="class" value="even-row"}
                       {else}
                       {assign var="class" value="odd-row"}
                       {/if}
		  	   <tr class= "fields {$class}">
			     {if $element.options_per_line != 0}
			       {if $element.field_title eq 'LL pays heat bill' or $element.field_title eq 'LL pays power bill' or $element.field_title eq 'Heat metering' or $element.field_title eq 'Electricity metering'}
      			       <td class="label">{$element.field_title}</td>
      			       <td class="crm-custom_data">
          		         {* sort by fails for option per line. Added a variable to iterate through the element array*}
          		           {foreach from=$element.field_value item=val}
              		   	       {$val}
          		           {/foreach}
                               </td>
			       {/if}
			      {else}
			      {if $element.field_title eq 'LL pays heat bill' or $element.field_title eq 'LL pays power bill' or $element.field_title eq 'Heat metering' or $element.field_title eq 'Electricity metering'}
                               <td class="label">{$element.field_title}</td>
                               {if $element.field_type == 'File'}
                                   {if $element.field_value.displayURL}
                                       <td class="crm-custom_data crm-displayURL"><a href="javascript:imagePopUp('{$element.field_value.imageURL}')" ><img src="{$element.field_value.displayURL}" height = "{$element.field_value.imageThumbHeight}" width="{$element.field_value.imageThumbWidth}"></a></td>
                                   {else}
                                       <td class="html-adjust crm-custom_data crm-fileURL"><a href="{$element.field_value.fileURL}">{$element.field_value.fileName}</a></td>
                                   {/if}
                               {elseif $element.field_data_type EQ 'ContactReference' && $element.contact_ref_id}
                                   {*Contact ref id passed if user has sufficient permissions - so make a link.*}
                                   <td class="html-adjust crm-custom-data crm-contact-reference">
                                      <a href="{crmURL p='civicrm/contact/view' q="reset=1&cid=`$element.contact_ref_id`"}" title="View contact">{$element.field_value}</a>
                                   </td>
                               {else}
                                   <td class="html-adjust crm-custom-data">{$element.field_value}</td>
				   {/if}
                               {/if}
                              {/if}
                             </tr>
{assign var="count" value=$count+1}
                  {/foreach}
        {/foreach}
{/foreach}

		     
	
	 	</table>
</div>

 {else}

        <div title="Landlord Info" id="contact-landlord" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
	    {if $contactinfo }
	    <div id="application-info">
	      <table>
			<tr class= "even-row" >
				<td class='label'>Landlord Name</td>
				<td>{$contactinfo.display_name}</td>
			</tr>
			<tr  class= "odd-row">
				<td class='label'>Permanent Address</td>
				<td>{$contactinfo.permanant_address}</td>
			</tr>
			{if $iselectric neq 1}	
	      		<tr  class= "even-row">
				<td class='label'>Additional Address 1</td>
			        <td>{$contactinfo.additional_add_1}</td>
			</tr>
			<tr  class= "odd-row">
				<td class='label'>Additional Address 2</td>
				<td>{$contactinfo.additional_add_2}</td>
			</tr>{/if}
			<tr  class= "even-row">
				<td class='label'>City</td>
				<td>{$contactinfo.city}</td>
			</tr>
			<tr  class= "odd-row">
				<td class='label'>Postal Code</td>
				<td>{$contactinfo.zip_postal_code}</td>
			</tr>
		
			<tr  class= "even-row">
				<td class='label'>Province</td>
				<td>{$contactinfo.state_province}</td>
			</tr>
			{if $contactinfo.country}
			<tr  class= "odd-row">
			
				<td class='label'>Country</td>
				<td>{$contactinfo.country}</td>
			</tr>
			{/if}
			<tr class= "even-row" >
				<td class='label'>Permanent Telephone</td>
				<td>{$contactinfo.permanant_telephone}</td>
			</tr>
 			{if $iselectric neq 1}
			<tr class= "odd-row" >
				<td class='label'>Landlord Email</td>
				<td>{$contactinfo.email}</td>
			</tr>
			{/if}			
	 	</table>
    	</div>
	{else}
		No Landlord found		
	{/if}
{/if}

            
		<div class="clear"></div>
    </div>
{/if}

{literal}
  <script>

  cj('#cancelAddApplicant').click(function() {
          var response = confirm("Are you sure you want to cancel adding Applicant and related details ?");
	  if ( response == true ) {
	     return true;
	  } else {
	     return false;
	  }
  });
  
  cj('#_qf_Landlord_upload').css( 'float', 'left' );

  cj(document).ready(function(){

    var actionString = "{/literal}{$actionString}{literal}";

    if ( actionString == 'add') {
      cj('#_qf_Landlord_cancel').click( function() {
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
 
     cj("#location_2_phone_1_phone").blur(function() {

        if ( cj(this).val().length >=10 ) {
	   var curval = cj(this).val().replace(/[^0-9]/g, "");
	   curval = "(" + curval.substring(0,3)+")" + curval.substring(3,6) +"-" + curval.substring(6,10);
	   cj(this).val(curval);
	}	
     });

  });
  </script>
{/literal}