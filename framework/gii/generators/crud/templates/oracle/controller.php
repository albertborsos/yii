<?php
    /**
     * This is the template for generating a controller class file for CRUD feature.
     * The following variables are available in this template:
     * - $this: the CrudCode object
     */
    ?>
<?php echo "<?php\n"; ?>

class <?php echo $this->controllerClass; ?> extends <?php echo $this->baseControllerClass."\n"; ?>
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/center';
    public $name='<?php echo $this->controllerClass; ?>';
    
    public function init()
	{
		parent::init();
		$this->actionName = array_merge($this->actionName, array(
                                                                 'save' => 'Mentés',
                                                                 ));
	}
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
                     'accessControl', // perform access control for CRUD operations
                     'postOnly + delete', // we only allow deletion via POST request
                     );
	}
    
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
                     array('allow', // create, admin-hoz be kell legyen jelentkezve
                           'actions'=>array('create','update','delete'),
                           //'expression' => 'Yii::app()->user->checkAccess("editor")',
                           ),
                     array('allow',  // allow all users to perform 'index' and 'view' actions
                           'actions'=>array('index','view', 'admin'),
                           //'expression' => 'Yii::app()->user->checkAccess("reader")',
                           ),
                     array('deny',  // deny all users
                           'users'=>array('*'),
                           ),
                     );
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
                                   'model'=>$this->loadModel($id),
                                   ));
	}
    
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new <?php echo $this->modelClass; ?>;
        
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
        
		if(isset($_POST['<?php echo $this->modelClass; ?>']))
		{
            $transaction = Yii::app()->db->beginTransaction();
            try{
                $model->attributes=Yii::app()->request->getPost('<?php echo $this->modelClass; ?>');
                if(!$model->save()){
                    $model->throw_exception('<h4><?php echo $this->modelClass; ?> mentése nem sikerült</h4>');
                }else{
                    $transaction->commit();
                    Yii::app()->user->setFlash('success', '<h4><?php echo $this->modelClass; ?> sikeresen létrehozva!</h4>');
                    $this->redirect(array('admin'));
                }
            }catch (Exception $e){
                $transaction->rollback();
                Yii::app()->user->setFlash('error', $e->getMessage());
            }
		}
        
		$this->render('create',array(
                                     'model'=>$model,
                                     ));
	}
    
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
        
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
        
		if(isset($_POST['<?php echo $this->modelClass; ?>']))
		{
            $transaction = Yii::app()->db->beginTransaction();
            try{
                $model->attributes=Yii::app()->request->getPost('<?php echo $this->modelClass; ?>');
                if(!$model->save()){
                    $model->throw_exception('<h4><?php echo $this->modelClass; ?> frissítése nem sikerült!</h4>');
                }else{
                    $transaction->commit();
                    Yii::app()->user->setFlash('success', '<h4><?php echo $this->modelClass; ?> sikeresen frissítve!</h4>');
                    $this->redirect(array('/update/'.$id));
                }
            }catch (Exception $e){
                $transaction->rollback();
                Yii::app()->user->setFlash('error', $e->getMessage());
            }
        }
        
		$this->render('update',array(
                                     'model'=>$model,
                                     ));
	}
    
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
        
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
    
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('<?php echo $this->modelClass; ?>');
		$this->render('index',array(
                                    'dataProvider'=>$dataProvider,
                                    ));
	}
    
	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
        $this->layout = '//layouts/fluid';
        
		$model=new <?php echo $this->modelClass; ?>('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['<?php echo $this->modelClass; ?>']))
			$model->attributes=$_GET['<?php echo $this->modelClass; ?>'];
        
		$this->render('admin',array(
                                    'model'=>$model,
                                    ));
	}
    
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return <?php echo $this->modelClass; ?> the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=<?php echo $this->modelClass; ?>::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
    
	/**
	 * Performs the AJAX validation.
	 * @param <?php echo $this->modelClass; ?> $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='<?php echo $this->class2id($this->modelClass); ?>-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
