<?php

$this->widget('bootstrap.widgets.TbGridView',array(
  'id'=>'catalog-property-grid',
  'dataProvider'=>$model->search(),
  'columns'=>array(
		'caption',
		array(
      'name'=>'type_id',
      'value'=>'$data->type->caption',
    ),
		array(
		  'header'=>'Раздел',
      'class'=>'CLinkColumn',
      'labelExpression'=>'$data->rubric->Caption',
      'urlExpression'=>'Yii::app()->controller->createUrl("SiteTreeManagement/view", array("node"=>$data->rubric_id));'
    ),
		'name',
		array(
      'name'=>'is_required',
      'value'=>'$data->is_required ? "да" : ""',
    ),
    array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
			'template' => '{update} {delete}',
			'updateButtonUrl'=>'Yii::app()->createUrl("admin/SiteTreeManagement/module", array('.
                         '"module"=>"rubricEdit", "id"=>$data->primaryKey, "node"=>$data->rubric_id))',
      'deleteButtonUrl'=>'Yii::app()->createUrl("admin/SiteTreeManagement/module",array('.
                         '"module"=>"rubricDelete", "id"=>$data->primaryKey, "node"=>$data->rubric_id))',                 
			'buttons'=>array(
        'update'=>array(
          'visible'=>'$data->rubric_id == Yii::app()->controller->node'
        ),
        'delete'=>array(
          'visible'=>'$data->rubric_id == Yii::app()->controller->node'
        ),
      )
		),
  )
)); 
?>

<div>
<?php
  $this->widget(
    'bootstrap.widgets.TbButton',
    array(
      'label'=>'Добавить свойство',
      'type'=>'primary',
      'url'=>Yii::app()->createUrl(
        "admin/SiteTreeManagement/module", 
        array(
          "module"=>"rubricAdd",
          "node"=>$model->rubric_id
        )
      )
    )
  );
?>
</div>
