<?php

/**
 * This is the model class for table "catalog__item_value".
 *
 * The followings are the available columns in table 'catalog__item_value':
 * @property string $id
 * @property string $item_id
 * @property string $property_id
 * @property string $value
 * @property integer $value_int
 * @property integer $value_str
 */
class CatalogItemValue extends CatalogModel
{
	protected $tableName = "item_value";

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('item_id, property_id', 'required'),
			array('value_int, value_str', 'numerical', 'integerOnly'=>true),
			array('item_id, property_id', 'length', 'max'=>11),
			array('value', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, item_id, property_id, value, value_int, value_str', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		  'property'=>array(self::BELONGS_TO, 'CatalogProperty', 'property_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'item_id' => 'Объект',
			'property_id' => 'Свойство',
			'value' => 'Значение',
			'value_int' => 'Значение числовое',
			'value_str' => 'Значение строковое',
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

		$criteria->compare('item_id',$this->item_id,true);
		$criteria->compare('property_id',$this->property_id,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('value_int',$this->value_int);
		$criteria->compare('value_str',$this->value_str);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CatalogItemValue the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
