<?php
  /**
   *  Поведение "Рубрика-каталога".
   *  
   *  Данное поведение позволяет присоединить к модели объекта свойства. 
   *  Поддерживается древовидная иерархия каталога, и наследование свойств
   *  потомками.
   *  
   *  Все действия со свойствами рубрики происходят через объект модели
   *  рубрики.
   *  Обрабатываются они пакетно после сохранения объекта. Т.о. возможно
   *  отложенное добавление рубрик, при создании новой рубрики.                           
   *   
   *  @property object[] $catalogRubricFields
   *  @property Closure|function Обработчик, вызываемый после изменения информации о рубриках   
   **/        
  class CatalogRubricBehavior extends CActiveRecordBehavior
  {
    /**
     * Название поля в модели, в котором содержится первичный ключ
     * @var string     
     **/         
    public $rubricFieldId = "id"; 
    
    /**
     * Название поля в модели, в котором содержатся все дети узла
     * @var string     
     **/ 
    public $childrenField = "";
    
    /**
     * Название поля в модели, в котором содержится родитель рубрики
     * @var string     
     **/ 
    public $parentField   = "";
    
    /**
     * Название связи в модели, в которой будут храниться все свойства рубрики
     * @var string     
     **/ 
    public $catalogFieldsRelationName = "catalogRubricFields";
    
    /**
     * Название связи, в которой будут хранится свойсва только этйо рубрики
     * @var string     
     **/ 
    public $catalogCurrentFieldsRelationName = "catalogCurrentRubricFields";
    
    /**
     * Бли ли действия по изменению информации о полях рубрики    
     * @var boolean
     **/         
    private $isChangedCatalogFields = false;
    
    /**
     * Статичное хранилище свойств рубрики. При древовидном обходе 
     * не понадобиться каждыйраз грузить информацию о родителях        
     * @var array
     **/         
    private static $_rubricFields;
    
    /**
     * Хранилище очереди действий со свойствами рубрики    
     * @var array
     **/         
    private $_queue = array();             
    
    /**
     * Инициализация поведения.
     * 
     * Присоединяем к модели две дополнительные свзяи
     *  * catalogFieldsRelationName - в ней хранится список всех свойств рубрики,
     *    с учетом родителских.
     *  * catalogCurrentFieldsRelationName - только свойства этйо рубрики,
     *    без наследования.                              
     **/              
     public function attach($owner)
     {
       parent::attach($owner);
       
       // Присоединяем к модели поля для конуретной рубрики с учетом древовидной 
       // иерархии
       $relationTable = CatalogRubricProperty::model()->tableName();
       $metaData = $this->getOwner()->getMetaData();
       $metaData->addRelation(
         $this->catalogFieldsRelationName,
         array(
           CActiveRecord::MANY_MANY, 
           'CatalogProperty',
           "{$relationTable}(rubric_id, property_id)",
           "alias"=>"relationTbl",
           'order'=>"catalogRubricFields_relationTbl.order_by"
         )
       );
       $metaData->addRelation(
         $this->catalogCurrentFieldsRelationName,
         array(CActiveRecord::HAS_MANY, 'CatalogProperty', "rubric_id")
       );
       self::$_rubricFields = array();
     }
     
     /**
      * Открепление поведения
      *
      **/                 
     public function detach($owner)
     {
       parent::detach($owner);
       
       $metaData = $this->getOwner()->getMetaData();
       $metaData->removeRelation($this->catalogFieldsRelationName);
       $metaData->removeRelation($this->catalogCurrentFieldsRelationName);
       unset(self::$_rubricFields);
     }
    
    /**
     * Создать кеш всех свойств рубрики.
     * 
     * При изменении информации о свойствах текущей рубрики следует обновить 
     * кеш у этой рубрики и у всех в нее вложенных.          
     *
     **/              
    public function regenerateCatalogFieldsCache()
    {
      // Формируем кеш всех свойств для данной рубрики
      $this->buildCatalogRubricHash();
    
      // обойдем всех детей, и запустим у них процесс регенерации свойств
      foreach ($this->getOwner()->{$this->childrenField} as $item) {
        // @todo добавить в базовый функционал CComponent метод на проверку
        // прикреплен ли behavior определенного класса?
        if ($item->asa('catalog')) {
          $item->regenerateCatalogFieldsCache();
        }
      }
    }
    
    /**
     * Удалить свойство из рубрики
     * 
     * @param integer $id      
     * @throws CException Объект с указанным Id не найден
     * @throws CException Объект с указанным Id не является свойством этой рубрики
     **/         
    public function deleteCatalogProperty($id)
    {
      $model = CatalogProperty::model()->findByPk($id);
      if ($model === null) {
        throw new CException("Catalog property with id '{$id}' is not found");
      }
      
      if (!$this->isMyProperty($model)) {
        throw new CException("Свойство с идентификатором '{$id}' не принадлежит рубрике ".$this->getOwner()->{$this->rubricFieldId});
      }
      
      $this->addQueueAction("delete", $model);
    }
    
    /**
     * Добавить свойство в рубрику
     * 
     * @param CatalogProperty $model
     **/  
    public function addCatalogProperty(CatalogProperty $model)
    {
      $this->addQueueAction("add", $model);
    }
    
    /**
     * Редактировать свойство рубрики
     * 
     * @param CatalogProperty $model
     * @throws CException Свойство не относится к данной рубрике     
     **/  
    public function editCatalogProperty(CatalogProperty $model)
    {
      if (!$this->isMyProperty($model)) {
        throw new CException("Свойство с идентификатором '{$id}' не принадлежит рубрике ".$this->getOwner()->{$this->rubricFieldId});
      }
    
      $this->addQueueAction("edit", $model);
    }
    
  	public function onAfterModifyProperty($event)
  	{
  		$this->raiseEvent('onAfterModifyProperty', $event);
  	}
    
    /**
     * Действия после создания нового объекта
     * 
     * При создании новой рубрики надо не забыть собрать информацию о 
     * совйствах родительских групп.               
     **/         
    public function afterConstruct()
    {
      $this->isChangedCatalogFields = true;
    }
    
    /**
     * Действия после сохранения объекта
     * 
     * Когда успешно сохранили объект рубрики, то выполняем действия над 
     * свойствами, и строим кеш своих и детских свойств.
     *           
     * @param CModelEvent $event
     **/         
    public function afterSave($event)
    {
      parent::afterSave($event);
      
      $this->processQueue();
      $this->clearQueueAction();
      
      if ($this->isChangedCatalogFields) {
        $this->regenerateCatalogFieldsCache();
        $this->isChangedCatalogFields = false;
      }
    }
   
    /**
     * Получить список своих полей для рубрики, с учетом кеширования данных
     * 
     * @return CatalogProperty[]|null           
     **/         
    public function getCurrentRubricFields()
    {
      $id = $this->getOwner()->{$this->rubricFieldId};
      
      if (!isset(self::$_rubricFields[$id])) {
        self::$_rubricFields[$id] = $this->getOwner()->{$this->catalogCurrentFieldsRelationName};
      }
    
      return self::$_rubricFields[$id];
    }
    
    public function getCatalogRubricModel()
    {      
      $model = new CatalogProperty('search');
		  $model->unsetAttributes();
		  $model->rubric_id = $this->getOwnerId();
		  return $model;
    }
    
  	protected function afterModifyProperty()
  	{
  		if($this->hasEventHandler('onAfterModifyProperty')) {
  			$this->onAfterModifyProperty(new CEvent($this));
  		}
  	}
    
    /**
     * Получить идентификатор модели рубрики
     * 
     * @return int          
     **/         
    protected function getOwnerId()
    { 
      return $this->getOwner()->{$this->rubricFieldId};
    }
    
    /**
     * Является ли свойство вложенным непосредственно в объект рубрики 
     *     
     * @param CatalogProperty $model    
     * @return boolean     
     **/         
    private function isMyProperty(CatalogProperty $model) {
    
      $relateds = $this->getOwner()->{$this->catalogCurrentFieldsRelationName};
      foreach ($relateds as $related) {
        if ($related->equals($model)) {
          return true;
        }
      }
      
      return false;
    }
    
    /**
     * Добавить действие в очередь на обработку
     *
     * @param string $action
     * @param CatalogProperty $model
     **/              
    private function addQueueAction($action, CatalogProperty $model)
    {
      $this->_queue[] = array($action, $model);
      $this->isChangedCatalogFields = true;
      $this->afterModifyProperty();
    }
    
    /**
     * Очистить очередь действий со свойствами рубрики
     **/         
    private function clearQueueAction()
    {
      $this->_queue = array();
    }
    
    /**
     * Обработать очередь действий со свойствами рубркии
     **/         
    private function processQueue()
    {
      // если не было ранее установлено транзакции, то создаем ее
      if (!Yii::app()->db->currentTransaction) { // only start transaction if none is running already
  		  $transaction = Yii::app()->db->beginTransaction();
  		}
      try {
        $id = $this->getOwnerId();
        foreach ($this->_queue as $task) {
          $action = $task[0]; $model = $task[1];
          switch ($action) {
            case "delete":
              if (!$model->delete()) {
                throw new CException("Couldn't delete property '{$model->id}: {$model->getErrorsString()}'");
              }
              break;
              
            case "add":
            case "edit":
              $model->rubric_id = $id;
              if (!$model->save()) {
                throw new CException("Couldn't save property '{$model->id}': {$model->getErrorsString()}");
              }
              break;
            
            default:
              throw new CException("Called undefined action '{$action}' for process queue");
              break;
          }
        }
        
        if ($transaction) {
          $transaction->commit();
        }
        $this->getOwner()->getRelated($this->catalogCurrentFieldsRelationName, true);
      }
      catch (Exception $e) {
        if ($transaction) {
          $transaction->rollback();
        }
        throw $e;
      }
    }
    
    /**
     * Сортировка полей рубрики на основе односвязного списка.
     * 
     * Сортировка пузырьком односвязного спсика, в котором указывается предыдущий 
     * элемент. Рубрики которые находятся на уровень выше имеют приоритет. 
     * 
     * @param CatalogProperty[] $rubrics
     * @return CatalogProperty[]            
     **/         
    private function sortFields(array $rubrics)
    {
      $map = array(); $items = $rubrics; $prevIteration = 0;
      do {
        foreach ($items as $index=>$property) {
          $prev = $property->prev_id;
          $newMap = array();
          if ($prev && !isset($map[$prev])) {
            continue;
          }
          
          $newMap = array();
          if (!$prev) {
            $newMap[$property->id] = $property;
          }
          foreach ($map as $key=>$value) {
            $newMap[$key] = $value;
            if ($key == $prev) {
              $newMap[$property->id] = $property;
            }
          }
          $map = $newMap;
          unset($items[$index]);
        }
        $curIteration = count($items);
        // Защита от бесконечного зацикливания
        if ($prevIteration == $curIteration) {
          throw new CException("Циклическая ссылка при сортировке полей для рубрики ".$this->getOwner()->{$this->rubricFieldId});
        }
        $prevIteration = $curIteration;
      }
      while (!empty($items));
      
      return $map;
    }
    
    /**
     * Строим упорядоченный список всех свойств, с учетом наследования, 
     * для указанной рубрики.     
     * 
     **/             
    private function buildCatalogRubricHash()
    {
      $fields = array();
      $owner = $this->getOwner();
      // Получить список всех полей для себя и родителей
      $item = $owner;
      do {
        // @todo добавить в базовый функционал CComponent метод на проверку
        // прикреплен ли behavior определенного класса?
        if ($item->asa('catalog') && ($itemFields = $item->getCurrentRubricFields())) {
          $fields = array_merge($fields, $itemFields);
        }
      }
      while($item = $item->{$this->parentField});
      
      // Сортируем кучу
      $fields = array_reverse($fields);
      $fields = $this->sortFields($fields);

      $id = $owner->{$this->rubricFieldId};
      // обновляем записи в связанной таблице для текущей рубрики
      
      // удаляем все старые
      CatalogRubricProperty::model()->deleteAllByAttributes(array("rubric_id"=>$id));
      
      // создаем новые
      $index = 1;  
      foreach ($fields as $field) {
        $model = new CatalogRubricProperty;
        $model->attributes = array(
          "rubric_id"=>$id,
          "property_id"=>$field->id,
          "order_by"=>$index++
        );
        $model->save();
      }
      $owner->getRelated($this->catalogFieldsRelationName, true);
    }
  } 
