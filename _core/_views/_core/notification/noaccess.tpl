{include file="_core.header.tpl"}

<script>
    jQuery(document).ready(function(){
        setTimeout(function(){
            window.location.href = "{$notification->url}";
        }, 5000);
    });
</script>

<div style="padding: 40px; ">
    <div class="alert alert-error">
        <h4>{$notification->message}</h4>
        <p></p>
        <p>Сейчас Вы будете перемещены на предыдущую страницу</p>
        <p><a href="{$notification->url}">Перейдите по этой ссылке, если ничего не произошло</a></p>
    </div>
</div>

{include file="_core.footer.tpl"}