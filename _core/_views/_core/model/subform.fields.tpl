{if $model->fields->getCount() == 0}
    Нет полей у модели
{else}
<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th></th>
        <th>#</th>
        <th>{CHtml::tableOrder("field_name", $model->fields->getFirstItem())}</th>
        <th>{CHtml::tableOrder("export_to_search", $model->fields->getFirstItem())}</th>
        <th>{CHtml::tableOrder("is_readers", $model->fields->getFirstItem())}</th>
        <th>{CHtml::tableOrder("is_authors", $model->fields->getFirstItem())}</th>
        <th>{CHtml::tableOrder("defaultTranslation", $model->fields->getFirstItem())}</th>
        <th>{CHtml::tableOrder("defaultTableTranslation", $model->fields->getFirstItem())}</th>
    </tr>
    {foreach $model->fields->getItems() as $field}
    <tr>
        <td><a href="#" class="icon-trash" onclick="if (confirm('Действительно удалить поле {$field->field_name}')) { location.href='fields.php?action=delete&id={$field->id}'; }; return false;"></a></td>
        <td>{counter}</td>
        <td>
            <a href="fields.php?action=edit&id={$field->getId()}">
                {$field->field_name}
            </a>
        </td>
        <td>
            {if $field->isExportable()}
                <i class="icon-ok exportSwitch" id="{$field->getId()}"></i>
            {else}
                <i class="icon-off exportSwitch" id="{$field->getId()}"></i>
            {/if}
        </td>
        <td>
            {if $field->isReaders()}
                <i class="icon-ok readersSwitch" id="{$field->getId()}"></i>
            {else}
                <i class="icon-off readersSwitch" id="{$field->getId()}"></i>
            {/if}
        </td>
        <td>
            {if $field->isAuthors()}
                <i class="icon-ok authorsSwitch" id="{$field->getId()}"></i>
            {else}
                <i class="icon-off authorsSwitch" id="{$field->getId()}"></i>
            {/if}
        </td>
        <td>{$field->getTranslationDefault()}</td>
        <td>{$field->getTranslationTableDefault()}</td>
    </tr>
    {/foreach}
</table>
{/if}

<script>
    jQuery(document).ready(function(){
        jQuery(".exportSwitch").css("cursor", "pointer");
        jQuery(".readersSwitch").css("cursor", "pointer");
        jQuery(".authorsSwitch").css("cursor", "pointer");
        function onStateChange(that, action) {
            var id = jQuery(that).attr("id");
            var image = jQuery(that);
            jQuery.ajax({
                url: "{$web_root}_modules/_core/fields.php",
                cache: false,
                type: "GET",
                data: {
                    "action": action,
                    "id": id
                },
                beforeSend: function() {
                    if (jQuery(image).hasClass("icon-ok")) {
                        jQuery(image).removeClass("icon-ok");
                    } else if (jQuery(image).hasClass("icon-off")) {
                        jQuery(image).removeClass("icon-off");
                    }
                    jQuery(image).addClass("icon-signal");
                },
                success: function(data){
                    if (jQuery(image).hasClass("icon-signal")) {
                        jQuery(image).removeClass("icon-signal");
                    }
                    if (data == "1") {
                        jQuery(image).addClass("icon-ok");
                    } else {
                        jQuery(image).addClass("icon-off");
                    }
                }
            });
        }
        jQuery(".readersSwitch").on("click", function(){
            onStateChange(this, "ChangeReaders");
        });
        jQuery(".authorsSwitch").on("click", function(){
            onStateChange(this, "ChangeAuthors");
        });
        jQuery(".exportSwitch").on("click", function(){
            onStateChange(this, "ChangeExport");
        });
    });
</script>