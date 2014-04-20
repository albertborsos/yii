<?php
/**
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 */
?>
<?php echo "<?php\n"; ?>

/**
 * This is the model class for table "<?php echo $tableName; ?>".
 *
 * The followings are the available columns in table '<?php echo $tableName; ?>':
<?php foreach($columns as $column): ?>
 * @property <?php echo $column->type.' $'.$column->name."\n"; ?>
<?php endforeach; ?>
<?php if(!empty($relations)): ?>
 *
 * The followings are the available model relations:
<?php foreach($relations as $name=>$relation): ?>
 * @property <?php
	if (preg_match("~^array\(self::([^,]+), '([^']+)', '([^']+)'\)$~", $relation, $matches))
    {
        $relationType = $matches[1];
        $relationModel = $matches[2];

        switch($relationType){
            case 'HAS_ONE':
                echo $relationModel.' $'.$name."\n";
            break;
            case 'BELONGS_TO':
                echo $relationModel.' $'.$name."\n";
            break;
            case 'HAS_MANY':
                echo $relationModel.'[] $'.$name."\n";
            break;
            case 'MANY_MANY':
                echo $relationModel.'[] $'.$name."\n";
            break;
            default:
                echo 'mixed $'.$name."\n";
        }
	}
    ?>
<?php endforeach; ?>
<?php endif; ?>
 */
class <?php echo $modelClass; ?> extends OracleActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '<?php echo $tableName; ?>';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
<?php foreach($rules as $rule): ?>
			<?php echo $rule.",\n"; ?>
<?php endforeach; ?>
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('<?php echo implode(', ', array_keys($columns)); ?>', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
<?php foreach($relations as $name=>$relation): ?>
			<?php echo "'$name' => $relation,\n"; ?>
<?php endforeach; ?>
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
<?php foreach($labels as $name=>$label): ?>
			<?php echo "'$name' => '$label',\n"; ?>
<?php endforeach; ?>
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

<?php
foreach($columns as $name=>$column)
{
	if($column->type==='string')
	{
		echo "\t\t\$criteria->compare('$name',\$this->$name,true);\n";
	}
	else
	{
		echo "\t\t\$criteria->compare('$name',\$this->$name);\n";
	}
}
?>

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

<?php if($connectionId!='db'):?>
	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()-><?php echo $connectionId ?>;
	}

<?php endif?>
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return <?php echo $modelClass; ?> the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    /**
     * ciklusban ezzel kell megjeleníteni a CLOB mező tartalmát
     * működéséhez afterFind()-ban engedélyezni kell a convertálást
     * <?php echo $modelClass; ?>::get_clob_value($model->ID, 'DESCRIPTION');
     */
    public static function get_clob_value($id, $column){
		$model = self::model()->findByPk($id);
		if (!is_null($model)){
			return $model->$column;
		}
	}

    public function afterFind()
	{
		parent::afterFind();
        // CLOB (resource) to string
		/*
        if (!is_null($this->DESCRIPTION) && is_resource($this->DESCRIPTION)){
			$this->DESCRIPTION = stream_get_contents($this->DESCRIPTION);
		}
         */
	}
    
    public function beforeValidate(){
        if (parent::beforeValidate()){
            return true;
        }else{
            return false;
        }
    }
    
    public function beforeSave(){
        if (parent::beforeSave()){
            /*
            if (!$this->isNewRecord){
				if (Historizer::historize($this)) {
					// csak akkor frissítse ezeket a mezőket
					// ha módosult a rekord
					$this->DATE_UPDATE = date('Y-m-d H:i:s');
					$this->USER_UPDATE = Yii::app()->user->id;
				}
			} else {
				$this->DATE_CREATE = date('Y-m-d H:i:s');
				$this->USER_CREATE = Yii::app()->user->id;
			}
			$this->DATE_CREATE = $this->convert_date_before_save($this->DATE_CREATE);
			$this->DATE_UPDATE = $this->convert_date_before_save($this->DATE_UPDATE);
            */
            
            /*
             // CLOB to tmp
             $this->tmp['DESCRIPTION'] = $this->DESCRIPTION;
             $this->DESCRIPTION        = null;
             */
            return true;
        }else{
            return false;
        }
    }
    public function afterSave() {
        parent::afterSave();
        // ide jön a kódod
        /*
        // CLOB update
        if ($this->isNewRecord) {
			$subquery = 'SELECT ID FROM '.$this->tableName().' ORDER BY ID DESC';
			$sql      = 'SELECT * FROM ('.$subquery.') WHERE ROWNUM < 2';
			$id       = Yii::app()->db->createCommand($sql)->queryScalar();
		}else{
			$id = $this->getPrimaryKey();
		}
        
		$sql = 'UPDATE '.$this->tableName().' SET'
        . ' DESCRIPTION=:description'
        . ' WHERE ID=:id';
		$command = Yii::app()->db->createCommand($sql);
		$command->bindParam(':description', $this->tmp['DESCRIPTION'], PDO::PARAM_STR, strlen($this->tmp['DESCRIPTION']));
		$command->bindParam(':id', $id);
		$command->execute();
        */
        
        //$this->DATE_CREATE = $this->convert_date_after_save($this->DATE_CREATE);
		//$this->DATE_UPDATE = $this->convert_date_after_save($this->DATE_UPDATE);
    }
    public function beforeDelete() {
        if (parent::beforeDelete()){
            Historizer::historize($this);
            return true;
        }else{
            return false;
        }
    }
}
