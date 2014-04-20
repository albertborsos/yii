
<h3>View <?php echo $this->modelClass." #<?php echo \$model->{$this->tableSchema->primaryKey}; ?>"; ?></h3>

<?php echo "<?php"; ?> $this->widget('yiiwheels.widgets.detail.WhDetailView', array(
                                                                      'data'=>$model,
                                                                      'attributes'=>array(
                                                                                          <?php
                                                                                          foreach($this->tableSchema->columns as $column)
                                                                                          echo "\t\t'".$column->name."',\n";
                                                                                          ?>
                                                                                          ),
                                                                      )); ?>
