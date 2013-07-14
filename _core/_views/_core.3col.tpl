{include file="_core.header.tpl"}

    <div class="asu_header row-fluid">
        {if !$wap_mode}
            <div class="span2">
                <a href="/"><div class="asu_header_asu_logo"></div></a>
            </div>
        {/if}
        <div class="asu_header_content span8">
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
        {if !$wap_mode}
            <div class="span2">
                <a href="/"><div class="asu_header_ugatu_logo"></div></a>
            </div>
        {/if}
    </div>

    <div class="row-fluid">
        {if !$wap_mode}
        <div class="span2">
            {block name="asu_left"}
                {include file="_menumanager/menu.mainMenu.tpl"}
            {/block}
        </div>

        <div class="span10 asu_center_container">
        {else}
        <div class="asu_center_container center_wap" id="asu_center_container">
        {/if}
            <div class="span11 asu_center">
                {if (CSession::isAuth())}
                    {if (CSession::getCurrentUser()->getStatus() == "преподаватель")}
                        {include file="_menumanager/menu.adminMenu.tpl"}
                    {/if}
                {/if}

                {block name="asu_center"}
                {/block}
            </div>
            <div class="span1 asu_right">
                {block name="asu_right"}{/block}
            </div>
        </div>
    </div>

    <div class="asu_footer row-fluid">
    </div>

{include file="_core.footer.tpl"}