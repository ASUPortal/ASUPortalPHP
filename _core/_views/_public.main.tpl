{include file="_core.header.tpl"}

<div class="base_wrapper">
    <div class="asu_header">
        {if !$wap_mode}
            <a href="/"><div class="asu_header_asu_logo"></div></a>
        {/if}

        <div class="asu_header_content_container">
            {if !$wap_mode}
                <a href="/"><div class="asu_header_ugatu_logo"></div></a>
            {/if}
            <div class="asu_header_content">
                <p>Официальный портал кафедры АСУ</p>
                <p>
                    <strong>Сегодня: {CUtils::getDayOfWeekName(time())} {date("d.m.Y")} <font size="+2">{CUtils::getStudyWeekNumber()}</font>-я учебная неделя</strong>
                </p>
                <p class="asu_auth_info">
                    {if (CSession::isAuth())}
                    <a href="{CUtils::getLecturersLink()}?onGet=1&amp;idlect={CSession::getCurrentUser()->getId()}">
                        {if is_null(CSession::getCurrentPerson())}
                            {CSession::getCurrentUser()->getName()}
                        {else}
                            {CSession::getCurrentPerson()->getName()}
                        {/if}
                    </a>
                    <a href="{CUtils::getLogoutLink()}">выход</a>
                    {else}
                    <a href="{CUtils::getLoginLink()}" onclick="$('#asu_auth').show(); return false; ">авторизация</a>

                <div id="asu_auth">
                    <form action="{CUtils::getLoginLink()}" method="POST">
                        <p><strong>Авторизация пользователя</strong></p>

                        <p>
                            {CHtml::label("Логин", "login")} *
                            {CHtml::textField("login")}
                        </p>

                        <p>
                            {CHtml::label("Пароль", "password")} *
                            {CHtml::passwordField("password")}
                        </p>

                        <p>
                            {CHtml::checkBox("saveAuth", "1")}
                            {CHtml::label("Запомнить на две недели", "saveAuth")}
                        </p>

                        <p>
                            {CHtml::link("Восстановление пароля", "$web_root/_modules/_acl_manager/?action=restorePassword")}
                        </p>

                        <p>
                            {CHtml::submit("Вход")}
                        </p>
                    </form>
                </div>
                {/if}
                </p>
            </div>

            <div style="clear: both;"></div>
        </div>

        <div style="clear: both;"></div>
    </div>

    <div class="asu_body">
        {if !$wap_mode}
        <div class="asu_left align_left" id="asu_left_menu">
            {block name="asu_left"}
                {include file="_menumanager/menu.mainMenu.tpl"}
            {/block}
        </div>

        <div class="asu_hidemenu align_left">
            {block name="asu_hidemenu"}
                {include file="_core.hidemenu.tpl"}
            {/block}
        </div>

        <div class="asu_center_container" id="asu_center_container">
            {else}
            <div class="asu_center_container center_wap" id="asu_center_container">
                {/if}
                <div class="asu_right align_right">
                    {block name="asu_right"}
                    {/block}
                </div>

                <div class="asu_center">
                    {if (CSession::isAuth())}
                        {if (CSession::getCurrentUser()->getStatus() == "преподаватель")}
                            {include file="_menumanager/menu.adminMenu.tpl"}
                        {/if}
                    {/if}

                    {block name="asu_center"}
                    {/block}
                </div>

                <div style="clear: both;"></div>
            </div>

            <div style="clear: both;"></div>
        </div>

        <div class="asu_footer">
        </div>
    </div>

{include file="_core.footer.tpl"}