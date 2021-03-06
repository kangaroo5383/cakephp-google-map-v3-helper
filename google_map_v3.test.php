<?php

App::import('Helper', 'Tools.GoogleMapV3');
App::import('Vendor', 'MyCakeTestCase');

class GoogleMapHelperTest extends MyCakeTestCase {

	function startCase() {
		$this->GoogleMapV3 = new GoogleMapV3Helper();
		$this->GoogleMapV3->initHelpers();
	}

	function tearDown() {

	}

	function testObject() {
		$this->assertTrue(is_a($this->GoogleMapV3, 'GoogleMapV3Helper'));
	}

	
	function testUrlLink() {
		echo $this->_header(__FUNCTION__);
		
		$url = $this->GoogleMapV3->url(array('to'=>'Munich, Germany'));
		echo h($url);
		echo BR.BR;
		
		$link = $this->GoogleMapV3->link('To Munich!', array('to'=>'Munich, Germany'));
		echo h($link);
		echo BR.BR;

	}
	
	function testStaticPaths() {
		echo '<h3>Paths</h3>';
		$m = $this->pathElements = array(
			array(
				'path' => array('Berlin', 'Stuttgart'),
				'color' => 'green',
			),
			array(
				'path' => array('44.2,11.1', '43.1,12.2', '44.3,11.3', '43.3,12.3'),
			),
			array(
				'path' => array(array('lat'=>'48.1','lng'=>'11.1'), array('lat'=>'48.4','lng'=>'11.2')), //'Frankfurt'
				'color' => 'red',
				'weight' => 10
			)
		);

		$is = $this->GoogleMapV3->staticPaths($m);
		echo pr(h($is));


		$options = array(
			'paths' => $is
		);
		$is = $this->GoogleMapV3->staticMapLink('My Title', $options);
		echo h($is).BR.BR;

		$is = $this->GoogleMapV3->staticMap($options);
		echo $is;
	}

	function testStaticMarkers() {
		echo '<h3>Markers</h3>';
		$m = $this->markerElements = array(
			array(
				'address' => '44.3,11.2',
			),
			array(
				'address' => '44.2,11.1',
			)
		);
		$is = $this->GoogleMapV3->staticMarkers($m, array('color'=>'red', 'char'=>'C', 'shadow'=>'false'));
		echo returns(h($is));

		$options = array(
			'markers' => $is
		);
		$is = $this->GoogleMapV3->staticMap($options);
		echo h($is);
		echo $is;
	}


//	http://maps.google.com/staticmap?size=500x500&maptype=hybrid&markers=color:red|label:S|48.3,11.2&sensor=false
//	http://maps.google.com/maps/api/staticmap?size=512x512&maptype=roadmap&markers=color:blue|label:S|40.702147,-74.015794&markers=color:green|label:G|40.711614,-74.012318&markers=color:red|color:red|label:C|40.718217,-73.998284&sensor=false

	function testStatic() {
		echo '<h3>StaticMap</h3>';
		$m = array(
			array(
				'address' => 'Berlin',
				'color' => 'yellow',
				'char' => 'Z',
				'shadow' => 'true'
			),
			array(
				'lat' => '44.2',
				'lng' => '11.1',
				'color' => '#0000FF',
				'char' => '1',
				'shadow' => 'false'
			)
		);

		$options = array(
			'markers' => $this->GoogleMapV3->staticMarkers($m)
		);
		echo returns(h($options['markers'])).BR;

		$is = $this->GoogleMapV3->staticMapUrl($options);
		echo h($is);
		echo BR.BR;
		
		$is = $this->GoogleMapV3->staticMapLink('MyLink', $options);
		echo h($is);
		echo BR.BR;

		$is = $this->GoogleMapV3->staticMap($options);
		echo h($is).BR;
		echo $is;
		echo BR.BR;

		$options = array(
			'size' => '200x100',
			'center' => true
		);
		$is = $this->GoogleMapV3->staticMapLink('MyTitle', $options);
		echo h($is);
		echo BR.BR;
		$attr = array(
			'title'=>'Yeah'
		);
		$is = $this->GoogleMapV3->staticMap($options, $attr);
		echo h($is).BR;
		echo $is;
		echo BR.BR;


		$pos = array(
			array('lat'=>48.1, 'lng'=>'11.1'),
			array('lat'=>48.2, 'lng'=>'11.2'),
		);
		$options = array(
			'markers' => $this->GoogleMapV3->staticMarkers($pos)
		);
		
		
		$attr = array('url'=>$this->GoogleMapV3->url(array('to'=>'Munich, Germany')));
		$is = $this->GoogleMapV3->staticMap($options, $attr);
		echo h($is).BR;
		echo $is;

		echo BR.BR.BR;

		$url = $this->GoogleMapV3->url(array('to'=>'Munich, Germany'));
		$attr = array(
			'title'=>'Yeah'
		);
		$image = $this->GoogleMapV3->staticMap($options, $attr);
		$link = $this->GoogleMapV3->Html->link($image, $url, array('escape'=>false, 'target'=>'_blank'));
		echo h($link).BR;
		echo $link;
	}

	function testMarkerIcons() {
		$tests = array(
			array('green', null),
			array('black', null),
			array('purple', 'E'),
			array('', 'Z'),
		);
		foreach ($tests as $test) {
			$is = $this->GoogleMapV3->iconSet($test[0], $test[1]);
			echo $this->GoogleMapV3->Html->image($is['url']).BR;
		}
		
	}


	/**
	 * with default options
	 * 2010-12-18 ms
	 */
	function testDynamic() {
		echo '<h3>Map 1</h3>';
		echo '<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>';
		//echo $this->GoogleMapV3->map($defaul, array('style'=>'width:100%; height: 800px'));
		echo '<script type="text/javascript" src="'.$this->GoogleMapV3->apiUrl().'"></script>';
		echo '<script type="text/javascript" src="'.$this->GoogleMapV3->gearsUrl().'"></script>';

		$options = array(
			'geolocate' => true,
			'div' => array('id'=>'someothers'),
			'map' => array('zoom'=>6, 'type'=>'R', 'navOptions'=>array('style'=>'SMALL'), 'typeOptions' => array('style'=>'HORIZONTAL_BAR', 'pos'=>'RIGHT_CENTER'))
		);
		$result = $this->GoogleMapV3->map($options);
		$this->GoogleMapV3->addMarker(array('lat'=>48.69847,'lng'=>10.9514, 'title'=>'Marker', 'content'=>'Some Html-<b>Content</b>', 'icon'=>$this->GoogleMapV3->iconSet('green', 'E')));
	
		$this->GoogleMapV3->addMarker(array('lat'=>47.69847,'lng'=>11.9514, 'title'=>'Marker2', 'content'=>'Some more Html-<b>Content</b>'));
	

		$this->GoogleMapV3->addMarker(array('lat'=>47.19847,'lng'=>11.1514, 'title'=>'Marker3'));

		/*
		$options = array(
		'lat'=>48.15144,
		'lng'=>10.198,
		'content'=>'Thanks for using this'
	);
		$this->GoogleMapV3->addInfoWindow($options);
		//$this->GoogleMapV3->addEvent();
		*/

		$result .= $this->GoogleMapV3->script();

		echo $result;
	}


	/**
	 * more than 100 markers and it gets reaaally slow...
	 * 2010-12-18 ms
	 */
	function testDynamic2() {
		echo '<h3>Map 2</h3>';
		$options = array(
			'autoCenter' => true,
			'div' => array('id'=>'someother'), //'height'=>'111', 
			'map' => array('zoom'=>6, 'type'=>'H', 'typeOptions' => array('style'=>'DROPDOWN_MENU'))
		);
		echo $this->GoogleMapV3->map($options);
		$this->GoogleMapV3->addMarker(array('lat'=>47.69847,'lng'=>11.9514, 'title'=>'MarkerMUC', 'content'=>'Some more Html-<b>Content</b>'));
	
		for($i = 0; $i < 100; $i++) {
			$lat = mt_rand(46000, 54000) / 1000;
			$lng = mt_rand(2000, 20000) / 1000;
			$this->GoogleMapV3->addMarker(array('id'=>'m'.($i+1), 'lat'=>$lat,'lng'=>$lng, 'title'=>'Marker'.($i+1), 'content'=>'Lat: <b>'.$lat.'</b><br>Lng: <b>'.$lng.'</b>', 'icon'=>'http://google-maps-icons.googlecode.com/files/home.png'));
		}
	
		$js = "$('.mapAnchor').live('click', function(){
		var id = $(this).attr('rel');
		
		var match = matching[id];
		
		/*
		map.panTo(mapPoints[match]);
		mapMarkers[match].openInfoWindowHtml(mapWindows[match]);
		*/
		
		gInfoWindows1[0].setContent(gWindowContents1[match]);
		gInfoWindows1[0].open(map1, gMarkers1[match]);
	});";
	
		$this->GoogleMapV3->addCustom($js);
	
		echo $this->GoogleMapV3->script();

		echo '<a href="javascript:void(0)" class="mapAnchor" rel="m2">Marker2</a> ';
		echo '<a href="javascript:void(0)" class="mapAnchor" rel="m3">Marker3</a>';
	}

}
