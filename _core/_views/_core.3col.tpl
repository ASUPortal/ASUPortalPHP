{include file="_core.header.tpl"}

    <div class="container-fluid asu_header">
        <div class="row-fluid">
            <div class="span2">
                <a href="/"><div class="asu_header_asu_logo"></div></a>
            </div>

            <div class="asu_header_content span8">
                <p>Официальный портал кафедры АСУ</p>
                <p>
                    <strong>Сегодня: {CUtils::getDayOfWeekName(time())} {date("d.m.Y")} <font size="+2">{CUtils::getStudyWeekNumber()}</font>-я учебная неделя</strong>
                </p>
                <p class="asu_auth_info">
                    {if (CSession::isAuth())}
                    <a href="{CUtils::getLecturersLink()}?action=view&amp;id={CSession::getCurrentUser()->getId()}">
                        {if is_null(CSession::getCurrentPerson())}
                            {CSession::getCurrentUser()->getName()}
                        {else}
                            {CSession::getCurrentPerson()->getName()}
                        {/if}
                    </a>
                    <a href="{CUtils::getLogoutLink()}">выход</a>
                    {else}
                    <a href="#asu_auth" data-toggle="modal">авторизация</a>
                {/if}
                </p>
            </div>
            {if !$wap_mode}
                <div class="span2">
                    <a href="/"><div class="asu_header_ugatu_logo"></div></a>
                </div>
            {/if}
        </div>
    </div>

    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span2" id="asu_body_menu">
                <div class="row">
                    <div class="span12">
                        {block name="asu_left"}
                            {include file="_menumanager/menu.mainMenu.tpl"}
                        {/block}
                    </div>
                </div>
            </div>

            <div class="span10" id="asu_body_content">
                <div class="row">
                    <div class="span11">
                        {if (CSession::isAuth())}
                            {if (CSession::getCurrentUser()->getStatus() == "преподаватель") or (CSession::getCurrentUser()->getStatus() == "администратор")}
                                {include file="_menumanager/menu.adminMenu.tpl"}
                            {/if}
                        {/if}

                        {block name="asu_center"}
                        {/block}
                    </div>
                    <div class="span1">
                        {block name="asu_right"}{/block}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid asu_footer">
        <div class="row-fluid">

        </div>
    </div>

    {if !CSession::isAuth()}
        <div id="asu_auth" class="modal hide fade">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="myModalLabel">Авторизация пользователя</h3>
            </div>
            <div class="modal-body">
                <form action="{CUtils::getLoginLink()}" method="POST" class="form-horizontal">
                    <div class="control-group">
                        {CHtml::label("Логин", "login")}
                        <div class="controls">
                        {CHtml::textField("login")}
                        </div>
                    </div>

                    <div class="control-group">
                        {CHtml::label("Пароль", "password")}
                        <div class="controls">
                        {CHtml::passwordField("password")}
                        </div>
                    </div>

                    <div class="control-group">
                        {CHtml::label("Запомнить на две недели", "saveAuth")}
                        <div class="controls">
                            {CHtml::checkBox("saveAuth", "1")}
                        </div>
                    </div>


                    <div class="control-group">
                        <div class="controls">
                        {CHtml::submit("Вход", false)}
                        </div>
                    </div>
                </form>
            </div>
        </div>
    {/if}
{include file="_core.footer.tpl"}