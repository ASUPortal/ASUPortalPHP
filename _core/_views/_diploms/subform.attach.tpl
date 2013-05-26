<p>
    {CHtml::activeLabel("diplom_number", $diplom)}
    {CHtml::activeTextField("diplom_number", $diplom)}
    {CHtml::error("diplom_number", $diplom)}
</p>

<p>
    {CHtml::activeLabel("diplom_regnum", $diplom)}
    {CHtml::activeTextField("diplom_regnum", $diplom)}
    {CHtml::error("diplom_regnum", $diplom)}
</p>

<p>
    {CHtml::activeLabel("diplom_issuedate", $diplom)}
    {CHtml::activeDateField("diplom_issuedate", $diplom, "diplom_issuedate")}
    {CHtml::error("diplom_issuedate", $diplom)}
</p>