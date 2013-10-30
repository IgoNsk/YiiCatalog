<?php

  interface ICatalogPropertyType
  {
    public function readValue(CatalogItemValue $model);
    
    public function saveValue(CatalogItemValue $model, $value);
    
    //public function renderValue(CActiveRecord $model, $value);
  }
