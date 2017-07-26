{strip}
    {if (!empty($aJsFiles[$place]))}
        {foreach from=$aJsFiles[$place] key="file_key" item="file_path" }
            <script type='text/javascript' src='{$file_path}'></script>
        {/foreach}
    {/if}
    {if (!empty($aCssFiles[$place]))}
        {foreach from=$aCssFiles[$place] key="file_key" item="file_path" }
            <link rel="stylesheet" href="{$file_path}" type="text/css">
        {/foreach}
    {/if}
{/strip}