{strip}
    {if (!empty($aMeta))}
        {foreach from=$aMeta key="meta_key" item="meta" }
      <meta {$meta.prop_name}='{$meta.property}' content='{$meta.content}' />
    {/foreach}
    {/if}
{/strip}