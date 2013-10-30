<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'catalog-property-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation' => true,
	'clientOptions' => array( 
    'validateOnSubmit'=>true,
    'validateOnChange'=>false,
  ),
)); ?>

<?php echo $form->errorSummary($model); ?>

  <div class="controls controls-row">
	  <?php echo $form->textFieldRow($model,'caption',array('class'=>'span5','maxlength'=>200)); ?>
  </div>
  
  <div class="controls controls-row">
	  <?php echo $form->textFieldRow($model,'name',array('class'=>'span5','maxlength'=>100)); ?>
  </div>
  
  <div class="controls controls-row">
    <label for="CatalogProperty_rubric_id">Рубрика</label>
  	<?php 
      $item = TreeCatalogRubric::findById($model->rubric_id);
      echo '<span class="span5">'.CHtml::encode($item->Caption).'</span>';
    ?>
  </div>

  <div class="controls controls-row">
  	<?php 
  	  // предыдущие свойства
      $fields = array(""=>"Первым");
      foreach ($rubric->catalogRubricFields as $dict) {
        if ($dict->id != $model->id) {
          $fields[$dict->id] = "После «".$dict->caption."»";
        }
      }
      echo $form->dropDownListRow($model,'prev_id',$fields, array('class'=>'span5')); 
    ?>
  </div>

  <div class="controls controls-row">
  	<?php 
      $fields = array(""=>"");
      $dicts = CatalogPropertyType::model()->order("id")->findAll();
      foreach ($dicts as $dict) {
        $fields[$dict->id] = $dict->caption;
      }
      echo $form->dropDownListRow($model,'type_id',$fields, array('class'=>'span5')); 
    ?>
  </div>
  
  <div class="controls controls-row">
	  <?php echo $form->checkBoxRow($model,'is_required',array()); ?>
  </div>
  
  <div class="controls controls-row">
	  <?php echo $form->textAreaRow($model,'options',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>
  </div>
  
<div class="form-actions">
	<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
		)); ?>
</div>

<?php $this->endWidget(); ?>
