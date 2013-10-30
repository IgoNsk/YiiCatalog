<?php

  class TreeLeafFieldIteratorCatalogProperty extends TreeLeafFieldIterator {
  
    protected $defaultHtmlOptions = array(
      "class"=>"span12",
    );
  
    public function render(CActiveForm $form, array $options = array()) {
    
      $fieldName = $this->getName();
      $options = array_merge($this->defaultHtmlOptions, $options);
      
      $controller = new CatalogPropertyController("catalogProperty", Yii::app()->getModule("catalog"));
      $controller->rubricModel = $this->model;
      $controller->fieldName = $fieldName;
      $controller->options   = $options;

      $controller->actionAdmin();
    }
  }
