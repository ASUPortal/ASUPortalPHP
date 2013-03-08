<li id="orgid_<?php echo $item->getId(); ?>">
    <?php
        if (!is_null($item->getRole())) {
            echo "<b>".$item->getName() . "</b>, " . $item->getRole()->getValue();
        } else {
            echo "<b>".$item->getName()."</b>";
        }
        if ($item->getSubordinators()->getCount() > 0) {
            CUnorderedListWidget::display(array(
                'items' => $item->getSubordinators(),
                'itemTemplate' => "_orgStructureItem.html.php"
            ));
        }
    ?>
</li>