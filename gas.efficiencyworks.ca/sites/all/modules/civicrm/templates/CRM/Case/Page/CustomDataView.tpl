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
{* Custom Data view mode*}
{foreach from=$viewCustomData item=customValues key=customGroupId}
    {foreach from=$customValues item=cd_edit key=cvID}
	    {assign var='index' value=$groupId|cat:"_$cvID"}
	    <div id="{$cd_edit.name}" class="crm-accordion-wrapper crm-accordion_title-accordion {if $cd_edit.collapse_display eq 0 }crm-accordion-open{else}crm-accordion-closed{/if}">
             <div class="crm-accordion-header">
              <div class="icon crm-accordion-pointer"></div> 
		      {$cd_edit.title}
             </div>
            <div class="crm-accordion-body">			   
			{foreach from=$cd_edit.fields item=element key=field_id}
			    <table class="crm-info-panel">
				<tr>
				    {if $element.options_per_line != 0}
					<td class="label">{$element.field_title}</td>
					<td class="html-adjust">
					    {* sort by fails for option per line. Added a variable to iterate through the element array*}
					    {foreach from=$element.field_value item=val}
						{$val}<br/>
					    {/foreach}
					</td>
				    {else}
					<td class="label">{$element.field_title}</td>
					{if $element.field_type == 'File'}
					    {if $element.field_value.displayURL}
						<td class="html-adjust"><a href="javascript:imagePopUp('{$element.field_value.imageURL}')" ><img src="{$element.field_value.displayURL}" height = "100" width="100"></a></td>
					    {else}
						<td class="html-adjust"><a href="{$element.field_value.fileURL}">{$element.field_value.fileName}</a></td>
					    {/if}
					{else}
					    <td class="html-adjust">{$element.field_value}</td>
					{/if}
				    {/if}
				</tr>
			    </table>
			{/foreach}
			    <a href="#" class="button" style="margin-left: 6px;" onclick="updateCaseCustom({$caseID}, {$customGroupId}, {$contactID}, {$caseTypeID}); return false;"><span><div class="icon edit-icon"></div>{ts}Edit{/ts}</span></a><br/>

			</div>
			<div class="clear"></div>
		    </div>

    {/foreach}
{/foreach}
<div id="case_custom_edit"></div>

    {literal}
	<script type="text/javascript">
	cj(function() {
        cj().crmaccordions(); 
        });
	</script>
    {/literal}
{*currently delete is available only for tab custom data*}
{if $groupId}
<script type="text/javascript">
    {literal}
    function hideStatus( valueID, groupID ) {
        cj( '#statusmessg_'  + groupID + '_' + valueID ).hide( );
    }
    function showDelete( valueID, elementID, groupID, contactID ) {
        var confirmMsg = '{/literal}{ts}Are you sure you want to delete this record?{/ts}{literal} &nbsp; <a href="javascript:deleteCustomValue( ' + valueID + ',\'' + elementID + '\',' + groupID + ',' + contactID + ' );" style="text-decoration: underline;">{/literal}{ts}Yes{/ts}{literal}</a>&nbsp;&nbsp;&nbsp;<a href="javascript:hideStatus( ' + valueID + ', ' +  groupID + ' );" style="text-decoration: underline;">{/literal}{ts}No{/ts}{literal}</a>';
        cj( 'tr#statusmessg_' + groupID + '_' + valueID ).show( ).children().find('span').html( confirmMsg );
    }
    function deleteCustomValue( valueID, elementID, groupID, contactID ) {
        var postUrl = {/literal}"{crmURL p='civicrm/ajax/customvalue' h=0 }"{literal};
        cj.ajax({
          type: "POST",
          data:  "valueID=" + valueID + "&groupID=" + groupID +"&contactId=" + contactID + "&key={/literal}{crmKey name='civicrm/ajax/customvalue'}{literal}",    
          url: postUrl,
          success: function(html){
              cj( '#' + elementID ).hide( );
              var resourceBase   = {/literal}"{$config->resourceBase}"{literal};
              var successMsg = '{/literal}{ts}The selected record has been deleted.{/ts}{literal} &nbsp;&nbsp;<a href="javascript:hideStatus( ' + valueID + ',' + groupID + ');"><img title="{/literal}{ts}close{/ts}{literal}" src="' +resourceBase+'i/close.png"/></a>';
              cj( 'tr#statusmessg_'  + groupID + '_' + valueID ).show( ).children().find('span').html( successMsg );
			  var element = cj( '.ui-tabs-nav #tab_custom_' + groupID + ' a' );
			  cj(element).html(cj(element).attr('title') + ' ('+ html+') ');
          }
        });
    }
    {/literal}
</script>
{else}
<script type="text/javascript">
    {literal}
    function updateCaseCustom( entityID, groupID, contactID, subType ) {
      var dataURL = {/literal}"{crmURL p="civicrm/case/cd/edit" q="snippet=4&cgcount=1&action=update&reset=1" h=0}"{literal};
      dataURL = dataURL + '&type=Case&entityID=' + entityID + '&groupID=' + groupID + '&cid=' + contactID + ( subType ? '&subType=' + subType  : '');

      cj.ajax({
         url: dataURL,
         success: function( content ) {
             cj( '#case_custom_edit' ).show( ).html( content ).dialog({
         	    	title: "Update Custom Information",
             		modal: true,
             		width: 680, 
             		overlay: { 
             			opacity: 0.5, 
             			background: "black" 
             		},

                 close: function(event, ui) {
                        cj('#case_custom_edit').fadeOut(5000);
                 }
             });
         }
      });

    }
    {/literal}
</script>
{/if}

