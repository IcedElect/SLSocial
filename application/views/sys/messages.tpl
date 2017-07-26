{strip}
    {if (!empty($aMessages))}
        <div class="message_holder nest">
            {foreach from=$aMessages key="mess_status" item="messages_list" }
                {if (!empty($messages_list))}
                        {foreach from=$messages_list key="mess_key" item="mess_text" }
                            <div class="body-nest messages_list {$mess_status}">
                                <div class="message {$mess_key} {$mess_status}">
                                    {$mess_text}
                                </div>
                            </div>
                        {/foreach}
                {/if}
            {/foreach}
        </div>
    {/if}
{/strip}