<script>
    jQuery(document).ready(function(){
        jQuery("#subscribe").change(function(){
            jQuery.ajax({
                url: web_root + '_modules/_mail/index.php',
                async: true,
                data: {
                    value: jQuery(this).is(":checked"),
                    action: "subscribe"
                },
                method: "post"
            }).done(function(){
                jQuery.sticky("Настройки сохранены");
            });
        });
    });
</script>

<form class="form-horizontal">
    <div class="control-group">
        <label>Дублировать входящие сообщения на эл. почту</label>
        <div class="controls">
            <input type="checkbox" id="subscribe" value="1" {if !is_null(CSession::getCurrentUser()->getSubscription())}checked{/if}>
    </div></div>
</form>