<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Войти</title>

    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Electrolize|Josefin+Sans|Julius+Sans+One|Open+Sans+Condensed:300|PT+Sans+Caption|Slabo+27px" rel="stylesheet">

    <meta name=viewport content="width=device-width, initial-scale=1">

    <script src="{$path}scripts/jquery.min.js"></script>
    <script src="{$path}scripts/jquery.timeago.js"></script>
    <script src="{$path}scripts/jquery.timeago.ru.js"></script>
</head>
<body>

    <style>
        *{
            padding: 0px;
            margin: 0px;
            box-sizing: border-box;
        }
        html{
            position: relative;
            height: 100%;
        }
        body{
            position: relative;
            height: 100%;
            background: #28313c;
            font-size: 15px;
            font-family: 'Open Sans', 'Roboto';
            background: #2E3135;
            background: #fff;
            background: #f5f8fa;
            background: #d2383b;
            color: #dedede;
            color: #fff;
        }
        a:link, a:visited{
            text-decoration: none;
            color: #eee;
        }
        a:link:hover{
            text-decoration: none;
            cursor: pointer;
            color: #fff;
        }
        .login_box{
            width: 320px;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
        .block{
            margin: 20px 0px;
            text-align: center;
            max-height: 0px;
            overflow: hidden;
            transition: max-height .5s ease-out;
        }
        .block .inner{
            height: auto;
            padding: 0;
            abackground: #26282c;
            abackground: #f5f8fa;
            abackground: #fff;
            abox-shadow: 0 1px 0 0 #26282c, 0 0 0 1px #26282c;
            aborder: 1px solid #e6e6e6;
            padding: 5px;
        }
        .activate .block{
            max-height: 500px;
            transition: max-height .5s ease-in;
        }
        .button{
            font-size: 15px;
            padding: 7px 16px 8px;
            background: #d2383b;
            color: #dedede;
            abox-shadow: 0 3px 0 0 #cc181e;
            display: inline-block;
            border: 0px;
            border-radius: 3px;
        }
        .button.big_button{
            width: 100%;
            text-align: center;
            padding: 10px 16px 11px;
        }
        .button:hover{
            cursor: pointer;
            background: #b2292c;
            color: #fff;
        }
        .logo_holder{
            overflow: hidden;
            text-align: center;
        }
        .logo_holder img{
            width: 150px;
            height: 150px;
        }
        .loading .logo_holder img{
            transition: max-width .5s ease-out;
            -webkit-animation:spin 4s linear infinite;
            -moz-animation:spin 4s linear infinite;
            animation:spin 4s linear infinite;
        }

        .logo_holder span{
            overflow: hidden;
            display: inline-block;
            max-width: 0px;
            line-height: 80px;
            transition: all .3s ease-out;
            color: #fff;
            vertical-align: top;
            font-size: 36px;
        }
        /*-webkit-animation-name: opacity;
            -webkit-animation-duration: 10s;
            -webkit-animation-iteration-count: infinite;
            animation-name: opacity;
            animation-duration: 10s;
            animation-iteration-count: infinite;*/
        .activate .logo_holder{
        }
        .activate img{
            width: 80px;
            height: 80px;
            margin-right: 20px;
            transition: all .3s ease-in;
        }
        .activate span{
            max-width: 500px;
            transition: max-width .5s ease-in;
            font-weight: 100;
        }
        .form_row{
            margin: 7px 0px;
        }
        input[type="text"],
        input[type="password"]{
            line-height: 18px;
            background: 0 0;
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 3px;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            color: #fff;
            font-size: 14px;
            padding: 9px 8px 7px;
            -webkit-appearance: none;
            width: 100%;

            border: none;
            border-bottom: 1px solid rgba(255, 255, 255, .1);
        }

        input[type="text"]:focus,
        input[type="password"]:focus{
            outline: none;
        }
        {literal}
        ::-webkit-input-placeholder{color:#ddd;}
        ::-moz-placeholder{color:#ddd;}
        :-moz-placeholder{color:#ddd;}
        :-ms-input-placeholder{color:#ddd;}
        {/literal}

        span.error{
            display: block;
            padding: 7px 0px;
            color: #efefef;
        }

        @-moz-keyframes spin { 100% { -moz-transform: rotate(360deg); } }
        @-webkit-keyframes spin { 100% { -webkit-transform: rotate(360deg); } }
        @keyframes spin { 100% { -webkit-transform: rotate(360deg); transform:rotate(360deg); } }

        #login_but, #register_but{
            display: none;
        }
    </style>
    <form data-act="login" data-type="ajax" method="post" action="">
        <div class="login_box">
            <div class="logo_holder">
                <img src="/logo.png" alt="">
                <span>SternLight</span>
            </div>
            <div class="block">
                <div class="inner">
                    <input type="hidden" name="act" value="login">
                    <div class="form_row">
                        <input type="text" name="user_email" placeholder="Эл. почта">
                    </div>
                    <div class="form_row">
                        <input type="text" name="user_name" placeholder="Имя и фамилия" hidden>
                    </div>
                    <div class="form_row">
                        <input type="text" name="user_login" placeholder="Имя пользователя" hidden>
                    </div>
                    <div class="form_row">
                        <input type="password" name="user_password" placeholder="Пароль">
                    </div>
                </div>
                <span class="error"></span>
                <a href="#" onclick="jQuery('#login_but').hide();jQuery('#register_but, [name=user_name], [name=user_login]').show();jQuery('[name=act]').val('register');">Регистрация</a> | <a href="#">Забыл пароль?</a>
            </div>
            <button type="button" class="button big_button" id="toggle_block" onclick="jQuery('.login_box').addClass('activate');jQuery('#login_but').show();jQuery(this).hide()">Войти</button>
            <input type="submit" name="submit" class="button big_button" id="login_but" value="Войти">
            <input type="submit" name="submit" class="button big_button" id="register_but" value="Присоединиться">
        </div>
    </form>
    <script src="{$path}scripts/common.js"></script>
</body>
</html>