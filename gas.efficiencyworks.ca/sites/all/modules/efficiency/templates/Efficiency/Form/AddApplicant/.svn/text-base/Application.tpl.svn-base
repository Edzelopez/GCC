<div class="customdata-block">
{foreach from=$groupTree item=cd_edit key=group_id name=custom_sets}    
      {$cd_edit.title}
            <table class="custom-data-form">
                {foreach from=$cd_edit.fields item=element key=field_id}
                   {include file="CRM/Custom/Form/CustomField.tpl"}
                {/foreach}
            </table>
	    <div class="spacer"></div>
{/foreach}
</div>


<tr>

    <td class="grouplabel" colspan=2 >
        {$form.buttons._qf_Application_upload.html}
       {$form.buttons._qf_Application_cancel.html}
	</td>

</tr>