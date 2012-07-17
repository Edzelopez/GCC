{if $rows}
   <table cellpadding=0 cellspacing=1 border=1 width="90%" class="app T0">
     <tr>
        <th class="grouplabel">Measure</th>
        <th class="grouplabel">Installed</th>
        <th class="grouplabel">Costs</th>
        <th class="grouplabel">KWH</th>
      {if $iselectric eq 0} 
        <th class="grouplabel">Gas m3</th> 
      {/if}
        <th class="grouplabel">NPV</th>
        <th class="grouplabel">Funder</th>
        <th class="grouplabel">Work Order Issued</th>
        <th class="grouplabel">Installed</th>
        <th class="grouplabel">Verified</th>
        <th class="grouplabel">Pmt Authorized</th>
        <th class="grouplabel"></th>
     </tr>
{assign var="count" value=1}
     {foreach from=$rows item=row}
       {if ($count is not even)}  
	 {assign var="class" value="odd-row"}
           {else}
          {assign var="class" value="even-row"}
                       {/if}
        {if $row.measures eq PM } 
          <tr class = '{$class}'>
            <td>{$row.name}</td>
            <td>{$row.installed}</td>
            <td>{$config->defaultCurrencySymbol}&nbsp;{$row.costs}</td>
            <td>{$row.kwh}</td>
        {if $iselectric eq 0} 
            <td>{$row.m3saved}</td>
        {/if}
            <td>{$config->defaultCurrencySymbol}&nbsp;{$row.npv}</td>
            <td>{$row.funder}</td>
            <td align='center'></td>
            <td align='center'></td>
            <td align='center'></td>
            <td align='center'></td>
            <td></td>
          </tr>
        {/if}
{assign var="count" value=$count+1}
     {/foreach}
     <tr>
        <th>Basic</th>
        <th></th>
        <th>{$config->defaultCurrencySymbol}&nbsp;{$summary.bm_costs}</th>
        <th>{$summary.bm_kwh}</th>
        {* <th>{$summary.basic_m3}</th> *}
        <th>{$config->defaultCurrencySymbol}&nbsp;{$summary.bm_trc}</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
     </tr> 
{assign var="count" value=1}
     {foreach from=$rows item=row}
 {if ($count is not even)}  
	 {assign var="class" value="odd-row"}
           {else}
          {assign var="class" value="even-row"}
                       {/if}
        {if $row.measures eq XM } 
          <tr class = '{$class}'>
            <td>{$row.name}</td>
            <td>{$row.installed}</td>
            <td>{$config->defaultCurrencySymbol}&nbsp;{$row.costs}</td>
            <td>{$row.kwh}</td>
          {if $iselectric eq 0} 
            <td>{$row.m3saved}</td>
          {/if}
            <td>{$config->defaultCurrencySymbol}&nbsp;{$row.npv}</td>
            <td>{$row.funder}</td>
            <td align='center'>{$row.xm_workorder_issued|truncate:10:''|crmDate}</td>
            <td align='center'>{$row.xm_installed|truncate:10:''|crmDate}</td>
            <td align='center'>{$row.xm_verified|truncate:10:''|crmDate}</td>
            <td align='center'>{$row.xm_pay_authorized|truncate:10:''|crmDate}</td>
	    <td>{$row.action}</td>
          
          </tr>
        {/if}
{assign var="count" value=$count+1}
     {/foreach}
     <tr>
        <th>Extended</th>
        <th></th>
        <th>{$config->defaultCurrencySymbol}&nbsp;{$summary.xm_costs}</th>
        <th>{$summary.xm_kwh}</th>
        {if $iselectric eq 0}
         <th>{$summary.extended_m3}</th> 
        {/if}
        <th>{$config->defaultCurrencySymbol}&nbsp;{$summary.xm_trc}</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
     </tr>
     {if $iselectric} 
     <tr>
        <th>H&S Costs</th>
        <th></th>
	<th colspan="2">{$config->defaultCurrencySymbol}&nbsp;{$summary.h_s_cost}</th>
        <th></th>
        <th></th>
        <th colspan="2">H&S check done</th>
        <th colspan="2">{if $summary.h_s_checkdone} No {else} Yes {/if}</th>
        <th></th>
     </tr>
     {/if} 
     <tr>
        <th>Job Total</th>
        <th></th>
        <th>{$config->defaultCurrencySymbol}&nbsp;{$summary.job_costs}</th>
        <th>{$summary.job_kwh}</th>
       {if $iselectric eq 0}
        <th>{$summary.job_m3saved}</th>
       {/if} 
        <th>{$config->defaultCurrencySymbol}&nbsp;{$summary.job_npv}</th>
        <th></th>
	{if $iselectric}
        <th class="bcr">BCR:</th>
        <th colspan="2">{$summary.job_bcr} </th>
        <th></th>
	{else}
	<th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
	{/if}
     </tr> 
   </table>
{/if}

<br/>

{if $dateURL eq 1 && $action eq 4}
<a href="{crmURL p='civicrm/efficiency/applicant/projectdetails/update' q="reset=1&cid=`$cid`&context=audit&action=update"}">&nbsp;&raquo;{ts}Edit Date(s){/ts}</a>
{/if}

{if $action eq 2}
<div class="customdata-block">
{foreach from=$groupTree item=cd_edit key=group_id name=custom_sets}    
     
     <table id="Dates" class="custom-data-form" width=100%>
        <Th colspan=2>{$Title}</Th>
	  {if ($MesureTitle)}
	      <tr>
	      <td width=20%>{ts}Name{/ts}</td>
	      <td>{ts}{$MesureTitle}{/ts}</td>
	      </tr>
          {/if}
          {foreach from=$cd_edit.fields item=element key=field_id}
              {include file="CRM/Custom/Form/CustomField.tpl"}
          {/foreach}
     </table>
	    <div class="spacer"></div>
{/foreach}
</div>

<!-- <tr>

    <td class="grouplabel" colspan=2 >
       	{$form.buttons._qf_Projectdetails_submit.html}
        {$form.buttons._qf_Projectdetails_cancel.html}
    </td>

</tr> -->
{if $action eq 2}
<table>
    <tr>
       <td class="grouplabel" colspan=2 >
       	{$form.buttons._qf_Projectdetails_submit.html}
        {$form.buttons._qf_Projectdetails_cancel.html}
       </td>
    </tr>
</table>
{/if}
{else if $action eq 4}
{assign var="count" value=1}
{foreach from=$viewData item=customValues key=customGroupId}
        {foreach from=$customValues item=cd_edit key=cvID}
            <div class="customFieldGroup">
                <table id="{$cd_edit.name}" width=100%>
                  {foreach from=$cd_edit.fields item=element key=field_id}
 {if ($count is not even)}  
	 {assign var="class" value="odd-row"}
           {else}
          {assign var="class" value="even-row"}
                       {/if}
		  	   <tr class= "fields {$class}">
			     {if $iselectric}
			       {if $element.field_title eq 'WxAuditDATE'}
			     	 <td class="label" width=20%>Weatherization Audit</td>
                               {elseif $element.field_title eq 'QAVeriAudit'}
			         <td class="label" width=20%>Verification Audit</td>
                               {else}
      			         <td class="label" width=20%>{$element.field_title}</td>
                               {/if}
			     {else}
			        <td class="label" width=20%>{$element.field_title}</td>
			     {/if}
                             <td class="label" id="project_detail_date" width=20%>{$element.field_value}</td>			     
                           </tr>
{assign var="count" value=$count+1}
                  {/foreach}
                </table>
            </div>
        {/foreach}
{/foreach}

{/if}
<br>
{if $form.project_details_status}
    <table id='StatusTable' cellpadding=0 cellspacing=1 border=1 width="90%">
    	   <tr>
		<th>{ts}Status{/ts}</th>
	   </tr>
	   {foreach from=$form.project_details_status item=value key=key}
		{if ($value.html neq '<') and ($value.html neq '') and ($value.html neq 'p')  and ($value.html neq '0') and ($value.html neq 'g') and ($value.html neq '1') and ($value.html neq '2') } 
		    <tr><td>{$value.html}</td></tr>
		{/if}
 	   {/foreach}
 	   <tr>
	       <td id='setStatusButton' class="grouplabel" colspan=2 >
	       	   {$form.buttons._qf_Projectdetails_upload.html}
       	       </td>
           </tr>
    </table>
{/if}
<br>

{if $iselectric }
{literal}
    <script>
        cj(document).ready(function(){

		var currentDate  = new Date();
        	var currentYear  = currentDate.getFullYear();
        	var minYear      = currentYear;
        	var maxYear      = currentYear + 2;

        	cj('#Dates .dateplugin').datepicker('option', { minDate: new Date(minYear, 1 - 1, 1), maxDate: new Date(maxYear, 12 - 1, 31) });
                cj('#Dates .dateplugin').css({'marginLeft': '6px'});
                cj('#Dates .form-text').css({'marginLeft': '6px'});			      
        });
    </script>
{/literal}
{/if}