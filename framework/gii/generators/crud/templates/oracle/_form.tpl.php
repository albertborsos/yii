<div class="form">
    <?php echo "{form name=\"form\" id=\"{$this->class2id($this->modelClass)}-form\" htmlOptions=[\"class\"=>\"form-horizontal\"]}"; ?>

    <p class="note">A <span class="required">*</span>-al megjelölt mezők kitöltése kötelező!</p>

    <?php echo "{\$form->errorSummary(\$model)}\n"; ?>

    <?php
    foreach ($this->tableSchema->columns as $column) {
        if ($column->autoIncrement)
            continue;
        ?>
        <div class="control-group row">
            <?php echo "{" . $this->generateActiveLabelTPL($this->modelClass, $column) . "}\n"; ?>
            <div class="controls">
                <?php echo "{" . $this->generateActiveFieldTPL($this->modelClass, $column) . "}\n"; ?>
                <?php echo "{\$form->error(\$model,'{$column->name}')}\n"; ?>
            </div>
        </div>

    <?php
    }
    ?>
    <div class="control-group row">
        <div class="controls">
            <?php echo "{CHtml::submitButton('Mentés', ['class' => 'btn btn-primary'])}\n"; ?>
        </div>
    </div>

    <?php echo "{/form}\n"; ?>

</div><!-- form -->