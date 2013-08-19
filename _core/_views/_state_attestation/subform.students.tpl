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
            type: "get",
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

{foreach $form->commission->getDiplomsListByDate() as $date=>$diploms}
    <h2>{$date|date_format:"%d.%m.%Y"}</h2>

<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th></th>
        <th>#</th>
        <th>Студент</th>
        <th>Тема диплома</th>
    </tr>
    {foreach $diploms as $diplom}
        <tr>
            <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить диплом {$diplom->dipl_name}')) { removeDiplom({$diplom->getId()}); }; return false;"></a></td>
            <td>{counter}</td>
            <td>
                {if !is_null($diplom->student)}
                    {$diplom->student->getName()}
                {/if}
            </td>
            <td>
				<a href="{$web_root}_modules/_diploms/?action=edit&id={$diplom->getId()}">{$diplom->dipl_name}</a>
			</td>
        </tr>
    {/foreach}
</table>	
{/foreach}

</div>
