<li>
    <?php CHtml::link($item->getName()." (".$item->getTerms()->getCount().")", "_modules/_taxonomy/?id=".$item->getId()); ?>
</li>