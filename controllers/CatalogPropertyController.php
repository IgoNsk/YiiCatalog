<?php

  class CatalogPropertyController extends Controller {
    
    /**
     * @var CActiveRecord
     **/     
    public $rubricModel;
    
    /**
     * @var string
     **/     
    public $fieldName;   
    
    /**
     * @var array
     **/
    public $options = array();     
    
    /**
    * Creates a new model.
    * If creation is successful, the browser will be redirected to the 'view' page.
    */
    public function actionCreate($node)
    {
      $rubric = $node;
      $rubricModel = TreeCatalogRubric::findById($rubric);
      
      if ($rubricModel === null) {
        throw new CHttpException(404,'The requested rubric does not exist.');
      }
    
      $model = new CatalogProperty;
      $model->rubric_id = $rubric;
      $this->performAjaxValidation($model);
      
      if (isset($_POST['CatalogProperty'])) {
        $model->attributes = $_POST['CatalogProperty'];
        
        $rubricModel->addCatalogProperty($model);
        if ($rubricModel->save()) {
          $this->redirect(array('/admin/SiteTreeManagement/view','node'=>$model->rubric_id));
        }
      }
      
      $this->render('create',array(
        'model'=>$model,
        'rubric'=>$rubricModel
      ));
    }
    
    /**
    * Updates a particular model.
    * If update is successful, the browser will be redirected to the 'view' page.
    * @param integer $id the ID of the model to be updated
    */
    public function actionUpdate($id)
    {
      $model = $this->loadModel($id);
      $rubricModel = $model->rubric;
      $this->performAjaxValidation($model);
      
      if(isset($_POST['CatalogProperty'])) {
        $model->attributes = $_POST['CatalogProperty'];
        
        $rubricModel->addCatalogProperty($model);
        if ($rubricModel->save()) {
          $this->redirect(array('/admin/SiteTreeManagement/view','node'=>$model->rubric_id));
        }
      }
      
      $this->render('update',array(
        'model'=>$model,
        'rubric'=>$rubricModel
      ));
    }
    
    /**
    * Deletes a particular model.
    * If deletion is successful, the browser will be redirected to the 'admin' page.
    * @param integer $id the ID of the model to be deleted
    */
    public function actionDelete($id)
    {
      if(Yii::app()->request->isPostRequest) {
        // we only allow deletion via POST request
        $model = $this->loadModel($id);
        
        $model->rubric->deleteCatalogProperty($id);
        $model->rubric->save();
        
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax'])) {
          $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        }
      }
      else {
        throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
      }
    }
    
    /**
    * Manages all models.
    */
    public function actionAdmin()
    {
      $model = $this->rubricModel->catalogRubricModel;
      $this->renderPartial('admin', array(
        'model'=>$model,
      ));
    }
    
    /**
    * Returns the data model based on the primary key given in the GET variable.
    * If the data model is not found, an HTTP exception will be raised.
    * @param integer the ID of the model to be loaded
    */
    public function loadModel($id)
    {
      $model=CatalogProperty::model()->findByPk($id);
      if ($model===null) {
        throw new CHttpException(404,'The requested page does not exist.');
      }
      
      return $model;
    }
    
    /**
    * Performs the AJAX validation.
    * @param CModel the model to be validated
    */
    protected function performAjaxValidation($model)
    {
      if (isset($_POST['ajax']) && $_POST['ajax']==='catalog-property-form') {
        echo CActiveForm::validate($model);
        Yii::app()->end();
      }
    }
  }
