{strip}
    <ul class="menu {if $level > 0}sub_menu sub_level_{$level} {/if}">
        {$level = $level + 1}

        {foreach $aMenu as $key => $menu_item}
            <li class="menu_item">
                <a class="menu_link" href="{$menu_item->link}">{$menu_item->translate}</a>
                {if (isset($menu_item->children)) }
                    {include file="sys/sub_menu.tpl" aMenu=$menu_item->children level=$level}
                {/if}
            </li>
        {/foreach}
    </ul>
{/strip}