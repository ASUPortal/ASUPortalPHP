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

<form>
    <p>
        <label>Дублировать входящие сообщения на эл. почту</label>
        <input type="checkbox" id="subscribe" value="1" {if !is_null(CSession::getCurrentUser()->getSubscription())}checked{/if}>
    </p>
</form>