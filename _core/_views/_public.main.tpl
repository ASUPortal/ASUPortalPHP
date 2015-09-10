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
							<FORM NAME="searchF" id="searchF" METHOD="get" ACTION="{$web_root}p_search_detail.php">
						        <INPUT TYPE="text" NAME="q" SIZE=20 VALUE="" MAXLENGTH=160 placeholder="Поиск">
						        <br>
						        <INPUT TYPE=SUBMIT VALUE="Найти" style="display:none;">
						        <INPUT TYPE="hidden" NAME="r1" VALUE="on">
						        <INPUT TYPE="hidden" NAME="r2" VALUE="on">
						        <INPUT TYPE="hidden" NAME="r3" VALUE="on">
						        <INPUT TYPE="hidden" NAME="r4" VALUE="on">
					        </FORM>
                        	{include file="_public.vkwidget.tpl"}
                        <br>
                        <div align=center class=middle><b>Дружественные сайты:</b></div>
						<table width=160 align=center valign="top" border=0>
							<tr height=50 class=round_table><td colspan=3 align=center><a href=http://www.ugatu.ac.ru title="Официальный сайт УГАТУ" target="_blank">
								<img src="{$web_root}images/design/baners/baner4.gif" alt="Cайт УГАТУ" border=1></a></td></tr><br>
							<tr height=50 class=round_table><td colspan=3 align=center><a href=http://www.businessstudio.ru title="Управление бизнесом, бизнес-моделирование, бизнес-процесс, описание бизнес-процессов, оптимизация бизнес-процессов&nbsp;&mdash;&nbsp;Business Studio" target="_blank">
								<img src="{$web_root}images/design/baners/baner8.gif" alt="Cайт Business Studio" border=0 style="background-color:White;"></a></td></tr>
							<tr height=70 class=round_table><td colspan=3 align=center><a href=http://erp4students.ru title="Программа дистанционного обучения решениям SAP для студентов России и стран СНГ, организуемую университетом Дуйсбург-Эссена (Германия)   erp4students" target="_blank">
								<img src="{$web_root}images/design/baners/baner9.gif" alt="erp4students" border=0 style="background-color:White;"></a></td></tr>
			        		<tr height=50 class=round_table><td colspan=3 align=center><a href=http://www.library.ugatu.ac.ru/index.html title="Библиотека,электронный каталог УГАТУ" target="_blank">
								<img src="{$web_root}images/design/baners/baner6.gif" alt="Библиотека УГАТУ" border=1></a></td></tr>
			   			</table>
			   			<br><br>
                    	<div class="iv-embed" style="margin:0 auto;padding:0;border:0;width:202px;">
                    		<div class="iv-v" style="display:block;margin:0;padding:1px;border:0;background:#000;">
                    			<iframe class="iv-i" style="display:block;margin:0;padding:0;border:0;" src="//open.ivideon.com/embed/v2/?server=865afea6585a2e2e1007d995d5722f27&amp;camera=262144&amp;width=&amp;height=&amp;lang=ru" width="200" height="164" frameborder="0" allowfullscreen></iframe>
                    		</div>
                    		<div class="iv-b" style="display:block;margin:0;padding:0;border:0;">
                    			<div style="float:right;text-align:right;padding:0 0 10px;line-height:10px;">
                    				<a class="iv-a" style="font:10px Verdana,sans-serif;color:inherit;opacity:.6;" href="http://www.ivideon.com/" target="_blank">powered by Ivideon</a>
                    			</div>
                    			<div style="clear:both;height:0;overflow:hidden;">&nbsp;</div>
                    			<script src="http://open.ivideon.com/embed/v2/embedded.js"></script>
                    		</div>
                    	</div>
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
    
    {if !CSession::isAuth()}
        <div id="asu_auth" class="modal hide fade">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
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
                            {CHtml::link("Восстановление пароля", "{$web_root}_modules/_acl_manager/index.php?action=restorePassword")}
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