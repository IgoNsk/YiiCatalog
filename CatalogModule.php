<?php
  
  Yii::setPathOfAlias('CatalogModule' , dirname(__FILE__));

  class CatalogModule extends CWebModule
  {
    public $tablePrefix = 'catalog__';
  
    public function init()
    {
      $this->setImport(
        array(
          'application.modules.catalog.components.*',
          'application.modules.catalog.controllers.*',
          'application.modules.catalog.models.*',
        )
      );
    }
  }
