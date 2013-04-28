<script>
    jQuery(document).ready(function(){
        jQuery("#search").autocomplete({
            source: web_root + "_modules/_state_attestation/index.php?action=searchDiplom",
            minLength: 2,
            select: function(event, ui) {
                // добавляем в текущую комиссию студента
                jQuery.ajax({
                    url: "{$web_root}_modules/_state_attestation/index.php",
                    cache: false,
                    data: {
                        action: "addDiplom",
                        commission_id: {$form->commission->getId()},
                        diplom_id: ui.item.object_id
                    },
                    type: "post",
                    beforeSend: function(){
                        jQuery("#diploms_list").html('<img src="{$web_root}images/loading.gif">');
                    }
                }).done(function(data){
                    jQuery("#diploms_list").load("{$web_root}_modules/_state_attestation/index.php?action=loadDiplomsSubform&id={$form->commission->getId()}");
                });
            }
        });
        jQuery("#search").keypress(function(e){
            if (e.which == 13) {
                return false;
            }
        });
    });
    function removeDiplom(diplom_id) {
        jQuery.ajax({
            url: "{$web_root}_modules/_state_attestation/index.php",
            cache: false,
            data: {
                action: "removeDiplom",
                commission_id: {$form->commission->getId()},
                diplom_id: diplom_id
            },
            type: "post",
            beforeSend: function(){
                jQuery("#diploms_list").html('<img src="{$web_root}images/loading.gif">');
            }
        }).done(function(){
            jQuery("#diploms_list").load("{$web_root}_modules/_state_attestation/index.php?action=loadDiplomsSubform&id={$form->commission->getId()}");
        });
    }
</script>

<div id="diploms_list">
<table border="0" cellpadding="2" cellspacing="0" class="tableBlank">
    <tr>
        <td>
            <p>
                <input type="text" id="search" style="width: 100%; " placeholder="Поиск по теме диплома и фамилии студента">
            </p>
        </td>
    </tr>
</table>

<table border="1" cellpadding="2" cellspacing="0">
    <tr>
        <th></th>
        <th>#</th>
        <th>Студент</th>
        <th>Тема диплома</th>
    </tr>
    {foreach $form->commission->diploms->getItems() as $diplom}
        <tr>
            <td><a href="#" onclick="if (confirm('Действительно удалить диплом {$diplom->dipl_name}')) { removeDiplom({$diplom->getId()}); }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
            <td>{counter}</td>
            <td>
                {if !is_null($diplom->student)}
                    {$diplom->student->getName()}
                {/if}
            </td>
            <td>{$diplom->dipl_name}</td>
        </tr>
    {/foreach}
</table>
</div>