<?php
  use yii\grid\GridView;
  use yii\helpers\Html;
  use yii\helpers\Url;
  use kartik\date\DatePicker;
  use frontend\assets\SwitcheryAsset;
  SwitcheryAsset::register($this);
  $title = Yii::t('app', 'Orders by Province');
  $this->title = Yii::t('app', $title);
  $this->params['headerIcon'] = 'fa fa-shopping-cart';
  $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Report'), 'url' => Url::to(['summary/index'])];
  $this->params['breadcrumbs'][] = $this->title;
  $dataLat = [];
  $fromDate = '';
  $toDate = '';
  if(Yii::$app->request->get('fromDate')) $fromDate = Yii::$app->request->get('fromDate');
  if(Yii::$app->request->get('toDate'))$toDate = Yii::$app->request->get('toDate');
  foreach ($model->allModels as $key => $value){
    $dataLat[] = [
        'order' => $value['name_th'].": ". number_format($value['orders']),
        'price' =>  number_format($value['total'], 2),
        'lat' => $value['province_lat'],
        'lon' => $value['province_lon'],
    ];
  }
  $jsonLat = json_encode($dataLat);
  function setOption($title){
    return [
    'class' => 'form-control',
    'prompt' => $title
    ];
  }
  function genWedgit($model, $genColumn){
    echo GridView::widget([
      'dataProvider' => $model,
      'layout' => '{items}',
      'summary'=>'',
      'id' => 'date-list',
      'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
      'columns' => $genColumn
    ]);
    $grid = new GridView(['filterSelector' => 'select[name="per-page"]','dataProvider'=>$model, 'pager' => ['maxButtonCount'=>4]]);
    echo '<table width="100%">
        <tr>
          <td class="text-left">
             '.$grid->renderSummary() .'
          </td>
          <td class="text-right">'. $grid->renderPager(). '</td>
        </tr>
      </table>';
  }
  function genArrayIndex($model, $label, $attribute, $format = false){
    $data = [
      'label' => Yii::t('app', $label),
      'format' => 'raw',
      'attribute' => $attribute,
    ];
    if($format){
      $data['value'] = function($model) use ($attribute){
        if(is_numeric($model[$attribute])) return number_format($model[$attribute],0);
        else return $model[$attribute];
      };
    }
    else $data['value'] = $attribute;
    return $data;
  }
  
  
  $this->registerJsFile('https://maps.googleapis.com/maps/api/js?key=AIzaSyAK3RgqSLy1toc4lkh2JVFQ5ipuRB106vU');
  if(!(Yii::$app->request->get('province') || Yii::$app->request->get('fromDate') || Yii::$app->request->get('toDate'))){
      $js = <<<JS
          var map; 
          var i; 
          var jsonLat = $.parseJSON('$jsonLat');
          var arrMarker = new Array();
          var isShowAll = true;
          var isPrice = false;
          function setmap(map, title, lat, lng){ 
            var marker = new google.maps.Marker({         
            position: new google.maps.LatLng(lat, lng),        
            map: map, 
            title : (title)  
            }); 
            var infowindow = new google.maps.InfoWindow(
            ); 
            infowindow.setContent(title);         
            infowindow.open(map, marker); 
            marker.addListener('click', function() {
              infowindow.open(marker.get('map'), marker);
            });
            arrMarker.push(marker);
          } 
          function Map() { 
            map = new google.maps.Map(document.getElementById('map'), { 
                center: {lat: 12.847860, lng: 100.604274}, 
                zoom: 6 
              }); 
            genMark(true);
          }
          function genMark(limit, showPrice){
            clearMark();
            count = 1;
            
            for(var i in jsonLat){
              if(limit == true && count == 10) break;
                if(showPrice){
                  setmap(map, jsonLat[i].order + ' / ' + jsonLat[i].price + ' ฿', jsonLat[i].lat, jsonLat[i].lon);
                }else{
                  setmap(map, jsonLat[i].order , jsonLat[i].lat, jsonLat[i].lon);
                }
              count++;
            }
          }
          function clearMark(){
            for(var i in arrMarker){
                arrMarker[i].setMap(null);
              }
          }
          $().ready(function(){
              Map();
              $("#price").change(function(){
                 if($(this).prop('checked')){ 
                  isPrice = true;
                  genMark(isShowAll, isPrice);
                }else{ 
                  isPrice = false;
                  genMark(isShowAll, isPrice);
                }
              });
              $("#showAll").change(function(){
                if($(this).prop('checked')){ 
                  isShowAll = false;
                  genMark(isShowAll, isPrice);
                }else{ 
                  isShowAll = true;
                  genMark(isShowAll, isPrice);
                }
              });
          });
JS;
        $this->registerJs($js, static::POS_END);
  }
?>
<div class="ibox">
  <div class="ibox-title"><h5><?php echo Yii::t('app', $title) ?></h5></div>
  <div class="ibox-content">
    <?php
      echo Html::beginForm(Url::to(['sell/order-province']), 'get');
      ?>
        <div class="row form-group">
            <div class="col-md-2 col-xs-2 col-lg-2">
              <label class="control-label" style="display:block"><?php echo Yii::t('app', 'Select Province') ?> </label>
               <?php
                echo  Html::dropDownList('province', Yii::$app->request->get('province'), $selectProvince, setOption('Select Province'));
               ?>
            </div>
            <div class="col-lg-3">
              <label class="control-label"><?php echo Yii::t('app', 'Date from') ?></label>
              <?php echo DatePicker::widget([
                  'name' => 'fromDate',
                  'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                  'value' => $fromDate,
                  'pluginOptions' => [
                      'autoclose'=>true,
                      'format' => 'dd-m-yyyy'
                  ]
                ]);
              ?>
            </div>

            <div class="col-lg-3">
              <label class="control-label"><?php echo Yii::t('app', 'Date to') ?></label>
              <?php echo DatePicker::widget([
                  'name' => 'toDate',
                  'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                  'value' => $toDate ,
                  'pluginOptions' => [
                      'autoclose'=>true,
                      'format' => 'dd-m-yyyy'
                  ]
                ]);
              ?>
            </div>
            <div class="col-md-2 col-xs-2 col-lg-2">
               <label class="control-label" style="display:block">&nbsp; </label>
               <?php echo Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
            </div>
      </div>
    <?php if(!(Yii::$app->request->get('province') || Yii::$app->request->get('fromDate') || Yii::$app->request->get('toDate'))):?>
      <hr>
      <div class="form-group row">
        <div class="col-lg-12">
         
        </div>
      </div>
      <div class="form-group row" >
        <div class="col-lg-12" style="position:relative;">
          <div  style="width:80px;margin-left:125px;top:10px;position:absolute;z-index: 999; background-color:#FFFFFF"  >
            <div class="col-lg-12 col-xs-12 col-md-12">
              <div class="form-group row text-center" >
                  <lable class="control-label"><b>Option</b></lable>
               </div>
               <div class="form-group row">
                  <table align="center">
                    <tr>
                      <td  style="font-size:10px;text-align:right"><?php echo Yii::t('app', 'price')?> : &nbsp;</td>
                      <td>
                        <input type="checkbox" id="price">   
                      </td>
                    </tr>
                    <tr>
                       <td style="font-size:10px;text-align:right"><?php echo Yii::t('app', 'show all')?> : &nbsp;</td>
                       <td>
                            <input type="checkbox" id="showAll">
                       </td>
                    </tr>
                  </table>
               </div>
             </div>
          </div>
          <div class="col-lg-12 col-md-12 col-xs-12" style="height:600px" id="map">
          </div>
        </div>
      </div>
    <?php endif?>
    <div class="form-group row">
      <div class="col-lg-12">
        <?php 
          echo Html::endForm();
          $genColumn[] = genArrayIndex($model, 'Name ', 'name_th');
          $genColumn[] = genArrayIndex($model, 'Orders' , 'orders', true);
          $genColumn[] = genArrayIndex($model, 'Total', 'total', true);
          genWedgit($model, $genColumn);
        ?>
      </div>
    </div>
  </div>
</div>