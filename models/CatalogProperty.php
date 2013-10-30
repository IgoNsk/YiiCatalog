<?php
require_once('CatalogModel.php');
/**
 * This is the model class for table "catalog__property".
 *
 * The followings are the available columns in table 'catalog__property':
 * @property string $id
 * @property string $caption
 * @property string $name
 * @property string $rubric_id
 * @property string $prev_id
 * @property string $type_id
 * @property string $is_required
 * @property string $options
 */
class CatalogProperty extends CatalogModel
{
  protected $tableName = "property";

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('caption, rubric_id, type_id', 'required'), // name
			array('caption', 'length', 'max'=>200),
			array('name', 'length', 'max'=>100),
			array('rubric_id, prev_id, type_id', 'length', 'max'=>11),
			array('is_required', 'length', 'max'=>2),
			array('options', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, caption, name, rubric_id, prev_id, type_id, is_required, options', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		  "type"=>array(self::BELONGS_TO, 'CatalogPropertyType', 'type_id'),
		  "rubrics"=>array(self::HAS_MANY, 'CatalogRubricProperty', 'property_id',
                       'order'=>'rubrics.order_by', "joinType"=>"join"
                      ),
      "rubric"=>array(self::BELONGS_TO, 'TreeCatalogRubric', 'rubric_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'caption' => 'Заголовок',
			'name' => 'Переменная',
			'rubric_id' => 'Рубрика',
			'prev_id' => 'Предыдущее свойство',
			'type_id' => 'Тип',
			'is_required' => 'Обязательно',
			'options' => 'Настройки',
		);
	}
	
	public function search()
	{
		$criteria = new CDbCriteria;
    
    $criteria->with = array("rubrics", "type", "rubric");
    $criteria->together = true;
    $criteria->order = "rubrics.order_by";
		$criteria->compare('rubrics.rubric_id', $this->rubric_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>false
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CatalogProperty the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
