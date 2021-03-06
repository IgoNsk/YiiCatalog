YiiCatalog
==========

Компонент для фреймворка Yii, который позволяет реализовать функционал универсального
древовидного каталога, с кастомизируемым набором полей для каждой рубрики.

## Особенности:
* Поддержка древовидной иерархии рубрик каталога;
* Неограниченный набор полей для каждой рубрики;
* Наследование полей в рубриках;
* Большой набор готовых типов полей (строка, число, выбор из списка и т.д.);
* Возможность реализации своего типа данных;
* Изоляция кода в виде модуля для Yii;
* Кеширование подготовленных данных о полях в товаре.

## Установка
* Скопировать расширение в папку с модулями проекта.
Например, /protected/modules/catalog/
* Залить дамп таблиц расширения (файл schema.sql) в используемую БД.
* Подключить модуль а файле конфигурации приложения
```php
'modules'=>array(
  'catalog'=>array(
  	'class'=>'application.modules.catalog.CatalogModule',
  ),
)
```
* Подключить и использовать behavior's реализующие функционал, к моделям 
рубрики и товара каталога, как указано ниже.
 

## Использование в публичной части проекта
* **Подключаем behavior к модели рубрики каталога**
```php
  public function behaviors()
  {
    Yii::app()->getModule("catalog");
    
    return array(
      "catalog"=>array(
        "class"=>"CatalogModule.components.CatalogRubricBehavior",
        "rubricFieldId"=>"Id", // название свойства, которео является первичным ключем рубрики
        "childrenField"=>"childrens", // свойство, в котором лежат все подрубрики
        "parentField"=>"parent", // свойство, ссылка на родительскую рубрику
      )
    );
  }
```
* **Получить список всех полей для рубрики**
```php
$rubrics = $model->catalogRubricFields;
```
* **Операции с полями рубрики**

Добавление нового свойства
```php
$prop = new CatalogProperty;
$prop->attributes = array(
  "caption"=>"Тест",
  "type_id"=>1
);
$model->addCatalogProperty($prop);
$model->save();
```

Редактирование существующено свойства
```php
$prop = CatalogProperty::model()->findByPk(5);
$prop->attributes = array(
  "prev_id"=>null
);
$model->editCatalogProperty($prop);
$model->save();
```

Удаление свойства
```php
$model->deleteCatalogProperty(5);
$model->save();
```
* **Подключаем behavior к модели товара каталога**
```php
  public function behaviors()
  {
    Yii::app()->getModule("catalog");
    
    return array(
      "catalog"=>array(
        "class"=>"CatalogModule.components.CatalogItemBehavior",
        "rubricFieldId"=>"rubric_id", // название свойства, ссылка на рубрику товара в модели
      )
    );
  }
```

## TODO
* Сделать функционал behavior товара каталога
* Сделать функционал управления значениями свойств товара в админке
* Написать unit тесты
* Написать документацию
