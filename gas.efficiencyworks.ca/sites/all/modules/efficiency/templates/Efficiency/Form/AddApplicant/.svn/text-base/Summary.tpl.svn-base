{if $action eq 2}
   <table cellpadding=0 cellspacing=1 border=1 width="90%" class="app">
    {foreach from=$form item=field key=keys}
      
     {if ($keys neq 'location') and ($field.label neq '') and ($field.label neq '<') and ($field.label neq 'S')}
       {if  ($keys neq 'attributes')}
        <tr>
            <td class="label">{$field.label}</td>
            <td class="fieldlabel">{$field.html}</td>
        </tr>
       {/if}	
     {/if}
     {if ($keys eq 'location')}
        {foreach from=$field item=one}
          {foreach from=$one item=address key=add1 }
             
            {if ($add1 eq 'address') } 
              {foreach from=$address item=addressItem key=addressItemKey}
                 <tr>
		 {if $addressItemKey eq 'postal_code_suffix' and $iselectric eq 1}
                    <td class="label">Unit#</td>
                    <td class="fieldlabel">{$addressItem.html}</td>

                 {elseif $addressItemKey eq 'supplemental_address_1' || $addressItemKey eq 'state_province_id'}
                    <td class="label">{$addressItem.label}</td>
                    <td class="fieldlabel">{$addressItem.html}</td>
	
		 {elseif $addressItemKey neq 'postal_code_suffix'}
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
  <tr>

    <td class="grouplabel" colspan=2 >
        {$form.buttons._qf_Summary_upload.html}
	    {$form.buttons._qf_Summary_submit.html}
        {$form.buttons._qf_Summary_cancel.html}
    </td>

  </tr>
</table>

{else if $action eq 4}

<div class="crm-block crm-content-block crm-contact-page">

    <div id="mainTabContainer" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
        
        <div title="Customer Info" id="contact-customerinfo" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
	    {if $contactinfo }
	    <div id="application-info">
	    	{if $editUrl}
	    	    <div id="edit-summary" class="" title="{ts}Edit Summary{/ts}">
	    	    	 <a href="{$editUrl}" title="{ts}Edit Summary{/ts}">&nbsp;{ts}>>Edit Summary{/ts}</a>     
	            </div><!-- End of Edit div--> 
        	{/if}
	        <table>
			<tr class = 'odd-row'>
				<td class='label'>First Name</td>
				<td>{$contactinfo.first_name}</td>
			</tr>
			<tr class = 'even-row'>
				<td class='label'>Last Name</td>
				<td>{$contactinfo.last_name}</td>
			</tr>
			<tr class = 'odd-row'>
			
				<td class='label'>
				   {if $iselectric eq 1}
				      HAP FileID
				   {else}
				      FileID
				   {/if}
				</td>
				<td>{$contactinfo.fileid }</td>
			</tr>
			<tr class = 'even-row'>
			        {if $iselectric eq 1}
				   <td class='label'>Address</td>
				{else}
				   <td class='label'>Permanant Address</td>
                                {/if}
				<td>{$contactinfo.permanant_address}</td>
			</tr>
			{if $iselectric eq 0}	
			<tr class = 'odd-row'>
				<td class='label'>
				Additional Address 1
				</td>
			        <td>{$contactinfo.additional_add_1}</td>
			</tr> 
			{/if}		
			{if $iselectric eq 0}
			<tr class = 'even-row'>
				<td class='label'>Additional Address 2</td>
				<td>{$contactinfo.additional_add_2}</td>
			</tr>
			{/if}
			<tr class = 'odd-row'>
				<td class='label'>City</td>
				<td>{$contactinfo.city}</td>
			</tr>
			<tr class = 'even-row'>
				<td class='label'>Postal Code</td>
				<td>{$contactinfo.zip_postal_code}</td>
			</tr>
			{if $iselectric eq 1}
			<tr class = 'odd-row'>
				<td class='label'>Unit#</td>
				<td>{$contactinfo.add_on_code}</td>
			</tr>
			{/if}
			{if $iselectric eq 1}
			<tr class = 'even-row'>
			{else}	<tr class = 'odd-row'> {/if}
				<td class='label'>State / Province</td>
				<td>{$contactinfo.state_province}</td>
			</tr>
			{if $iselectric eq 1}
			<tr class = 'odd-row'>
			{else}	<tr class = 'even-row'> {/if}
				<td class='label'>Country</td>
				<td>{$contactinfo.country}</td>
			</tr>
			{if $iselectric eq 1}
			<tr  class = 'even-row'>
			{else}	<tr class = 'odd-row'> {/if}
				<td class='label'>Permanent Telephone</td>
				<td>{$contactinfo.permanant_telephone}</td>
			</tr>
			{if $iselectric eq 1}
			   <tr class = 'odd-row'>
				<td class='label'>Email</td>
				<td>{$contactinfo.email}</td>
	                   </tr>
			{/if}
	 	</table>				
    	</div>
	{else}
		No contact found
	{/if}

            
	<div class="clear"></div>
    </div>

{/if}
</div><!-- /.crm-content-block -->

{literal}
  <script>
  
  cj('#_qf_Summary_upload').css( 'float', 'left' );

  cj(document).ready(function(){
  
  var actionString = "{/literal}{$actionString}{literal}";

    if ( actionString == 'add') {
      cj('#_qf_Summary_cancel').click( function() {
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
       if( cj(this).val().length >= 10 ) {
       	  var curval = cj(this).val().replace(/[^0-9]/g, "");
       	  curval = "(" + curval.substring(0,3)+")" + curval.substring(3,6) +"-" + curval.substring(6,10);
       	  cj(this).val(curval);
        }
       });

    });
	
  </script>
{/literal}