<?php

/**
 * This is the model class for table "catalog__property_value".
 *
 * The followings are the available columns in table 'catalog__property_value':
 * @property string $id
 * @property string $property_id
 * @property string $caption
 * @property integer $order_by
 * @property string $options
 */
class CatalogPropertyValue extends CatalogModel
{
	protected $tableName = "property_value";

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('property_id, caption', 'required'),
			array('order_by', 'numerical', 'integerOnly'=>true),
			array('property_id', 'length', 'max'=>11),
			array('caption', 'length', 'max'=>100),
			array('options', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('property_id, caption', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'property_id' => 'Свойство',
			'caption' => 'Заголовок',
			'order_by' => 'Порядок',
			'options' => 'Настройки',
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

		$criteria->compare('property_id',$this->property_id,true);
		$criteria->compare('caption',$this->caption,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CatalogPropertyValue the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
