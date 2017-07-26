{strip}
    {if (empty($element))}
        {$element = 'nav'}
    {/if}
    {if ($element != 'none')}
        <{$element} {if (!empty($class))} class="{$class}" {/if}>
    {/if}

    {if (!empty($aMenu))}
        {include file="sys/sub_menu.tpl" aMenu = $aMenu level='0'}
    {/if}

    {if ($element != 'none')}
        </{$element}>
    {/if}
{/strip}