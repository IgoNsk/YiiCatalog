<?php

/**
 * This is the model class for table "catalog__property_type".
 *
 * The followings are the available columns in table 'catalog__property_type':
 * @property string $id
 * @property string $caption
 * @property string $class_name
 */
class CatalogPropertyType extends CatalogModel
{
  protected $tableName = "property_type";

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('caption', 'length', 'max'=>100),
			array('class_name', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, caption, class_name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		  'values'=>array(self::HAS_MANY, 'CatalogPropertyValue', 'property_id'),
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
			'class_name' => 'Класс PHP',
		);
	}
	
	public function order($field, $direction = "asc")
  {
    $this->getDbCriteria()->mergeWith(array(
        'order'=>$field.' '.$direction,
    ));
    return $this;  
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

		$criteria->compare('caption',$this->caption,true);
		$criteria->compare('class_name',$this->class_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CatalogPropertyType the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
