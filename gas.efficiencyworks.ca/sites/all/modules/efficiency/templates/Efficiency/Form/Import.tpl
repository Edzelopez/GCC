{* GCC Import FAT *}<div id="upload-file" class="form-item">   <fieldset>    <legend>{ts}Import {$fat_fast}{/ts}</legend>    <table>    {foreach from=$form item=field key=keys}       {if ($keys neq frozen) and  ($keys neq javascript)and ($keys neq attributes)and ($keys neq requirednote)and ($keys neq errors)and ($keys neq  hidden ) and ($field.label neq '<') and ($field.label neq 'I')}           {if ( $field.name eq 'buttons' )}              <tr>	        <td>{$field.html}</td>	        <td><span><a id='cancelUrl' class="delete button" href="{$cancelUrl}">Cancel</a></span></td>              </tr>           {/if}           {if ( $field.name neq 'buttons' )}              <tr>                <td>{$field.label}</td>	        <td>{$field.html}</td>              </tr>           {/if}           {if ($field.name eq 'uploadFile')}              <tr>                <td></td>                <td class="description">{ts}Import CSV File here.{/ts}</td>              </tr>           {elseif ($field.name eq 'uploadFileFAST')}              <tr>                <td>                </td><td class="description">{ts}Import XLS File here.{/ts}</td>              </tr>           {elseif ($field.type eq 'file')}              <tr>                <td></td>                <td class="description">{ts}Do Not Import CSV/XLS File here.{/ts}</td>              </tr>           {/if}       {/if}  {/foreach}  </table>  </fieldset></div>