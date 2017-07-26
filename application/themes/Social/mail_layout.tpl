{strip}
    <!DOCTYPE html>
    <html>
    <body class="mail {$body_class}">
    <div class="wrap">
        <div class="middle_wrap " id="middle_wrap">
            <div class="content" id="main">
                {$message_body}
            </div>

        </div>
        <footer id="footer">
            <div class="made_by">
                {*$aVariables.madeby_text*}
            </div>
            <div class="copy_right">
                {*$aVariables.copyright_text*}
            </div>
        </footer>
    </div>
    </body>
    </html>
{/strip}
