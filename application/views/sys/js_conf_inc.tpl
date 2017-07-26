{strip}
    <script type="text/javascript">
        var aConf = {
            "base_url": '{$aConf.base_url}',
            {if (!empty($aConf.js_values))}
            {foreach $aConf.js_values as $key => $value}
            "{$key}": '{$value}',
            {/foreach}
            {/if}
            "enable_google": '{$aConf.enable_google}'
        };
    </script>
{/strip}