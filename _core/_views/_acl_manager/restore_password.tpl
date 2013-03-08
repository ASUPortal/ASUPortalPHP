{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Восстановление пароля</h2>

<p>Пожалуйста, укажите <b>Логин</b> или <b>ФИО</b>, который Вы использовали для входа на сайт. </p>
<form action="index.php" method="post">
    <input type="hidden" name="action" value="savePasswordRecoveryRequest">
    <p>
        {CHtml::activeLabel("credential", $request)}
        {CHtml::activeTextField("credential", $request)}
        {CHtml::error("credential", $request)}
    </p>
    
    <p>
        {CHtml::submit("Отправить")}
    </p>
</form>
{/block}

{block name="asu_right"}

{/block}