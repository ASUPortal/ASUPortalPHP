<script>
    jQuery(document).ready(function(){
		var searchResults = new Object();
        jQuery("#search").typeahead({
			minLength: 3,
			source: function(query, process){
				return jQuery.ajax({
					url: web_root + "_modules/_state_attestation/",
                    type: "get",
                    cache: false,
                    dataType: "json",
                    data: {
                        "term": query,
                        "action": "searchDiplom"
                    },
                    beforeSend: function(){
                        /**
                         * Показываем индикатор активности
                         */
                        jQuery("#search").css({
                            "background-image": 'url({$web_root}images/ajax-loader.gif)',
                            "background-repeat": "no-repeat",
                            "background-position": "95% center"
                        });
                    },
                    success: function(data){
                        var lookup = new Array();
                        searchResults = new Object();
                        for (var i = 0; i < data.length; i++) {
                            lookup.push(data[i].label);
                            searchResults[data[i].label] = data[i].object_id;
                        }
                        process(lookup);
                        jQuery("#search").css("background-image", "none");
                    }					
				});
			},
			updater: function(item){
				var value = searchResults[item];
				/**
				 * Добавляем диплом в текущую комиссию
				 */
				jQuery.ajax({
                    url: "{$web_root}_modules/_state_attestation/index.php",
                    cache: false,
                    data: {
                        action: "addDiplom",
                        commission_id: {$form->commission->getId()},
                        diplom_id: value
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
<table border="0" cellpadding="2" cellspacing="0" class="tableBlank" width="90%">
    <tr>
        <td>
            <p>
                <input type="text" id="search" style="width: 100%; " placeholder="Поиск по теме ВКР и фамилии студента">
            </p>
        </td>
    </tr>
</table>

{foreach $form->commission->getDiplomsListByDate() as $date=>$diploms}
	<h2>{$date|date_format:"%d.%m.%Y"} 
		Номер распоряжения:
		{$first = array_values($diploms)}
		{$first = $first[0]}
		{$first->num_order}
    </h2>
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th></th>
        <th>#</th>
        <th>Студент</th>
        <th>Тема ВКР</th>
    </tr>
    {if usort($diploms, array("CSABComissionController", "studentByProtocolSorter"))}
    {/if}
    {foreach $diploms as $diplom} 
        <tr>
            <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить тему ВКР {$diplom->dipl_name}')) { removeDiplom({$diplom->getId()}); }; return false;"></a></td>
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
