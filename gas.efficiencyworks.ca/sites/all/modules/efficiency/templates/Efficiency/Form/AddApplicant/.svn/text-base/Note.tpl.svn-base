{if $action eq 2}

{if !$form.note.value and $action neq 1 and $action neq 2}
<div class="messages status">
           <dl>
             <dt><img src="{$config->resourceBase}i/Inform.gif" alt="{ts}status{/ts}" /></dt>
               <dd>
                 {ts }There are no Notes, you can  <a href="{crmURL p='civicrm/efficiency/note' q="context=note&cid=`$cid`&reset=1&action=update"}">{ts}add one now{/ts}</a>.{/ts}
               </dd>
           </dl>
       </div>
{else}
<table cellpadding=0 cellspacing=1 border=1 width="90%" class="app">
<tr>
    <td class="grouplabel">
        <label>{$form.note.label}</label></td>
    <td class="fieldlabel">
        {$form.note.html}<br />
    </td>
</tr> 
{if $action eq 2 and $action neq 1}
<tr>
    <td class="grouplabel" style="text-align: left;">
        <label>{$form.auditornotes.label}</label></td>
    <td class="fieldlabel">
        {$form.auditornotes.html}<br />
    </td>
</tr> 
{/if}
 {if $iselectric }
<tr>
    <td class="grouplabel">
        <label>{$form.vvnotes.label}</label></td>
    <td class="fieldlabel">
        {$form.vvnotes.html}<br />
    </td>
</tr>

<tr>
    <td class="grouplabel">
        <label>{$form.hsnotes.label}</label></td>
    <td class="fieldlabel">
        {$form.hsnotes.html}<br />
    </td>
</tr>
{/if}
</table>
{/if}
{if $action eq 2 and $action neq 1}
<tr>
    <td class="grouplabel" colspan=2 >
     	{$form.buttons._qf_Note_submit.html}
	    {$form.buttons._qf_Note_cancel.html}
    </td>
</tr>
<br/>
{else if $action eq 2}
      <table cellpadding=0 cellspacing=1 border=1 width="90%" class="app">
      	     <tr>
	         <td class="grouplabel" colspan=2>
		     {$form.buttons._qf_Note_submit.html}
		     {$form.buttons._qf_Note_cancel.html}
                 </td>
	     </tr> 
      </table>
<br/>
{/if}
{else if $action eq 4}
<div class="crm-block crm-content-block crm-contact-page">

    <div id="mainTabContainer" class="ui-tabs ui-widget ui-widget-content ui-corner-all">

        <div title="Notes" id="contact-note" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
        {if $contactinfo}
	    <div id="contact-info">
		
             {if $editUrl}
	    	    <div id="edit-note" class="" title="{ts}Edit Note{/ts}">
	    	    	 <a href="{$editUrl}" title="{ts}Edit Note{/ts}">&nbsp;{ts}>>Edit Note{/ts}</a>     
	            </div><!-- End of Edit div--> 
             {/if}

	    {if $iselectric }
	        <table width=100%>
		      
			<tr class = 'odd-row'>
				<td class='label' width=20%>Notes</td>
				<td width=80%> 
				 {if $contactinfo.note}
				     {$contactinfo.note}
				 {/if}
				</td>
			</tr>
		        <tr class = 'even-row'>
				<td class='label' width=20%>Auditor Note</td>
				<td width=80%>
 				 {if $contactinfo.auditornotes}
				   {$contactinfo.auditornotes}
				 {/if}
				</td>
			</tr>	
			
			<tr class = 'odd-row'>
				<td class='label' width=20%>Verification Visit Notes</td>
				<td width=80%>
				 {if $contactinfo.vvnotes}
			        	 {$contactinfo.vvnotes}
			 	 {/if}
			       </td>
			</tr>
		
			{if $iselectric}
			<tr class = 'even-row'>
				<td class='label'>Health & Safety Notes</td>
				<td>
				 {if $contactinfo.hsnotes}
                                    {$contactinfo.hsnotes}
				 {/if}
				 </td>
			</tr>
					   {/if}     
		
		
	 	</table>
		{else}

		     <table width=100%>
		        {if $contactinfo.note}
			<tr class = 'odd-row'>
				<td class='label' width=20%>Note</td>
				<td width=80%>{$contactinfo.note}</td>
			</tr>
		        {/if}
                        {if $contactinfo.auditornotes}			
	                <tr class = 'even-row'>
				<td class='label' width=20%>Auditor Note</td>
				<td width=80%>{$contactinfo.auditornotes}</td>
			</tr>
			{/if}
			{if $contactinfo.hsnotes && $iselectric}
			<tr class = 'odd-row'>
				<td class='label' width=20%>Health & Safety Notes</td>
				<td width=80%>{$contactinfo.hsnotes}</td>
			</tr>
			{/if}
		
		
	 	     </table>
		{/if}
    	</div>
	{else}
		No Note found
	{/if}

				<div class="clear"></div>
    </div>

{/if}
</div><!-- /.crm-content-block -->

{literal}
  <script>

  cj('#_qf_Note_submit').css( 'float', 'left' );

  </script>
{/literal}