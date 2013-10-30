<?php

  class CatalogModel extends CActiveRecord
  {
    protected $tableName;
  	/**
  	 * @return string the associated database table name
  	 */
  	public function tableName()
  	{
  		return Yii::app()->getModule('catalog')->tablePrefix.$this->tableName;
  	}
  	
  	public function getErrorsString()
    {
      $result = "";
      foreach ($this->getErrors() as $attrname=>$errlist) {
        $result .= "  Errorred attribute: $attrname\n";
        foreach ($errlist as $err) {
          $result .= "    $err\n";
        }
      }
      
      return $result;
    }
  }
