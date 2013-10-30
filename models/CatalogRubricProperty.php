<?php

/**
 * This is the model class for table "catalog__rubric_property".
 *
 * The followings are the available columns in table 'catalog__rubric_property':
 * @property string $id
 * @property string $rubric_id
 * @property string $property_id
 * @property string $order_by
 */
class CatalogRubricProperty extends CatalogModel
{
	protected $tableName = "rubric_property";

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rubric_id, property_id', 'required'),
			array('rubric_id, property_id', 'length', 'max'=>11),
			array('order_by', 'length', 'max'=>3),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
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
			'rubric_id' => 'Rubric',
			'property_id' => 'Property',
			'order_by' => 'Order By',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CatalogRubricProperty the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
