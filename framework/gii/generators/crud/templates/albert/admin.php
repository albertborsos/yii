
<h3><?php echo $this->pluralize($this->class2name($this->modelClass))." áttekintése"; ?></h3>

<?php echo $this->filter_info(); ?>

<?php echo "<?php"; ?> $this->widget('yiiwheels.widgets.grid.WhGridView', array(
                                                                         'id'=>'<?php echo $this->class2id($this->modelClass); ?>-grid',
                                                                         'fixedHeader' => true,
                                                                         'type' => 'striped',
                                                                         'dataProvider'=>$model->search(),
                                                                         'filter'=>$model,
                                                                         'responsiveTable' => true,
                                                                         'template' => "{summary}{items}{pager}",
                                                                         'columns'=>array(
                                                                                          <?php
                                                                                          foreach($this->tableSchema->columns as $column)
                                                                                          {
                                                                                          echo "\t\tarray(\n";
                                                                                          echo "\t\t\t'name'  => '".$column->name."',\n";
                                                                                          echo "\t\t\t'type'  => 'raw',\n";
                                                                                          echo "\t\t\t'value' => '\$data[\"{$column->name}\"]',\n";
                                                                                          echo "\t\t),\n";
                                                                                          }
                                                                                          ?>
                                                                                          array(
                                                                                                'header' => 'Utolsó módosítás',
                                                                                                'type' => 'raw',
                                                                                                'value' => 'AActiveRecord::get_last_modified_info($data)',
                                                                                                'htmlOptions' => array(
                                                                                                                       'style' => 'text-align:center',
                                                                                                                       ),
                                                                                          ),
                                                                                          array(
                                                                                                'class' => 'bootstrap.widgets.TbButtonColumn',
                                                                                                'template' => '{update}{delete}',
                                                                                                'buttons' => array(
                                                                                                                   'update' => array(
                                                                                                                                     'label' => 'Módosítás',
                                                                                                                                     'icon' => 'edit',
                                                                                                                                     //                    'url' => 'Yii::app()->createUrl("<?php echo $this->getModelClass(); ?>/update/".$data["<?php echo $this->tableSchema->primaryKey; ?>"])',
                                                                                                                                     'options' => array(
                                                                                                                                                        'class' => 'btn btn-small',
                                                                                                                                                        ),
                                                                                                                                     ),
                                                                                                                   'delete' => array(
                                                                                                                                     'label' => 'Eltávolítás',
                                                                                                                                     'icon' => 'white remove',
                                                                                                                                     //                    'url' => 'Yii::app()->createUrl("<?php echo $this->getModelClass(); ?>/delete/".$data["<?php echo $this->tableSchema->primaryKey; ?>"])',
                                                                                                                                     'options' => array(
                                                                                                                                                        'class' => 'btn btn-small btn-danger',
                                                                                                                                                        ),
                                                                                                                                     ),
                                                                                                                   ),
                                                                                                'htmlOptions' => array(
                                                                                                                       'style' => 'min-width:80px;'
                                                                                                                       )
                                                                                                ),
                                                                                          ),
                                                                         )); ?>
