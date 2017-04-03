<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models;

/* @var $this yii\web\View */
/* @var $searchModel app\models\VenuesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Venues';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="venues-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); 

		$form = ActiveForm::begin([
			     'layout' => 'horizontal',
			     'fieldConfig' => [
			         'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
			         'horizontalCssClasses' => [
			             'label' => 'col-md-4',
			             'offset' => 'col-md-offset-1',
			             'wrapper' => 'col-md-12',
			             'error' => '',
			             'hint' => '',
			         ],
			     ],
			 ]);
		?>
		<div class="form-group row">
		<div class="col-md-3">
		<?php 
		echo $form->field ($searchModel, 'type')->textInput(array('placeholder'=>'What type are you looking for?',
																	'append'=>'fa-fa-users'))->label(false);
		?>
		</div>
		<div class="col-md-2">
		<?php echo $form->field($searchModel, 'choices')->dropDownList(array('0'=>'popular', '1'=>'social'))->label(false);?>
		</div>
		<div class="col-md-2">
        <?php 
        echo $form->field($searchModel, 'distance_limit')->textInput(array('placeholder'=>'Optional(10kms)'))->label(false);
		?>
		</div>
		<div class="col-md-2">
		<?php 
        echo $form->field($searchModel, 'rating')->textInput(array('placeholder'=>'Rating(Max 5)'))->label(false);
		?>
		</div>
		<div class="col-md-3">
        	<?= Html::submitButton('Search <span class="glyphicon glyphicon-search"></span>', ['class' => 'btn btn-success']) ?>
        </div>
        </div>

	<?php 
		ActiveForm::end();
    ?>
    <div class="form-group row">
    <div class="col-md-4">
    <?php // GridView::widget([
//         'dataProvider' => $dataProvider,
//         'filterModel' => $searchModel,
//         'columns' => [
//             ['class' => 'yii\grid\SerialColumn'],

//             'id',
//             'type',
//             'name',
//             'address',

//             ['class' => 'yii\grid\ActionColumn'],
//         ],
//     ]); 

    echo GridView::widget([
    		'dataProvider' => $dataProvider,
    		'columns' => [
			'id',
			'latitude',
			'longitude',
			'address',
			'type',
    		'distance',
			'avg_rating',
			
    		],
    ]); 
    ?>
    </div>
    <div class="col-md-5 col-md-offset-3">
    <div id="map" style="height:500px;padding:10px; !important">
    </div>
    </div>
    <script>

      function initMap() {

        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 3,
          center: {lat: <?php echo $user->userLocation->latitude ?>, lng: <?php echo $user->userLocation->latitude ?>}
        });

        document.getElementById('map').style.display="block";
        // Create an array of alphabetical characters used to label the markers.
        var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Add some markers to the map.
        // Note: The code uses the JavaScript Array.prototype.map() method to
        // create an array of markers based on a given "locations" array.
        // The map() method here has nothing to do with the Google Maps API.
        var markers = locations.map(function(location, i) {
          return new google.maps.Marker({
            position: location,
            label: labels[i % labels.length]
          });
        });

        // Add a marker clusterer to manage the markers.
        var markerCluster = new MarkerClusterer(map, markers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
      }
      var locations = [
                       <?php 
                       $count1 = count($dataProvider->getModels());
                       $count = 0;
                       foreach ($dataProvider->models as $aLocation)
                       {
                       			$count++;
                       			echo "{lat:".$aLocation['latitude'].", lng:".$aLocation['longitude']."}	";
                       			if($count != $count1)
                       				echo ",";
                       }
                       
                       ?>
      ]
    </script>
    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCPxAAfKs4SLAb7lrEGLChn2Ob4Z23ZyHI&callback=initMap">
    </script>
</div>
</div>
