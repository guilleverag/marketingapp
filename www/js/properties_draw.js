var system_width; 
var pushPinActive;

//Funcion General de latitude/longitude
function getLatLon(lat, lon){
	return new google.maps.LatLng(lat, lon);
}
//Custom Toolbar
function CenterControl(controlDiv, map, mapClass){
	// We set up a variable for this since we're adding event listeners later.
  	var control = this;
	controlDiv.style.clear = 'both';
	
	var panShape = document.createElement('div');
	panShape.id = 'pan_shape';
	controlDiv.appendChild(panShape);
	
	var tableShape = document.createElement('table');
	tableShape.style.backgroundColor = 'orange';
	panShape.appendChild(tableShape);
	
	var trTool = document.createElement('tr');
	tableShape.appendChild(trTool);
	
	//Tool Pan
	var tdPan = document.createElement('td');
	tdPan.id = mapClass.idPan;
	tdPan.className = 'a_pan';
	tdPan.align = 'middle';
	tdPan.width = 80;
	
	trTool.appendChild(tdPan);
	trTool.appendChild(document.createElement('td'));
	
	//Tool Draw
	var tdDraw = document.createElement('td');
	tdDraw.id = mapClass.idDraw;
	tdDraw.className = 'd_draw';
	tdDraw.align = 'middle';
	tdDraw.width = 80;
	
	trTool.appendChild(tdDraw);
	trTool.appendChild(document.createElement('td'));
	
	//Tool Poly
	var tdPoly = document.createElement('td');
	tdPoly.id = mapClass.idPoly;
	tdPoly.className = 'd_poly';
	tdPoly.align = 'middle';
	tdPoly.width = 80;
	
	trTool.appendChild(tdPoly);
	trTool.appendChild(document.createElement('td'));
	
	//Tool Circle
	var tdCircle = document.createElement('td');
	tdCircle.id = mapClass.idCircle;
	tdCircle.className = 'd_circle';
	tdCircle.align = 'middle';
	tdCircle.width = 80;
	
	trTool.appendChild(tdCircle);
	trTool.appendChild(document.createElement('td'));
	
	
	//Tool Clear
	var tdClear = document.createElement('td');
	tdClear.id = mapClass.idClear;
	tdClear.align = 'middle';
	tdClear.width = 80;
	tdClear.style.cursor = 'pointer';
	
	var clearImg = document.createElement('img');
	clearImg.src = '//www.reifax.com/img/reset_map.jpg';
	
	trTool.appendChild(tdClear);
	tdClear.appendChild(clearImg);
	trTool.appendChild(document.createElement('td'));
	
	//Tool MaxMin
	var tdMaxMin = document.createElement('td');
	tdMaxMin.id = mapClass.idMaxMin;
	tdMaxMin.className = 'd_maxmin';
	tdMaxMin.align = 'middle';
	tdMaxMin.width = 80;
	
	trTool.appendChild(tdMaxMin);
	
	//Set up the click event listener
	tdPan.addEventListener('click', function (e){
		mapClass.mouseMove(e,mapClass);
	});
	tdDraw.addEventListener('click', function (e){
		mapClass.drawRect(e,mapClass);
	});
	tdPoly.addEventListener('click', function (e){
		mapClass.drawPoly(e,mapClass);
	});
	tdCircle.addEventListener('click', function (e){
		mapClass.drawCircle(e,mapClass);
	});
	tdClear.addEventListener('click', function (e){
		mapClass.clearShape(e,mapClass);
	});
	tdMaxMin.addEventListener('click', function (e){
		mapClass.mapMaxMin(e,mapClass);
	});
}

XimaMap = function(id,idl,idbar,idpan,iddraw,idpoly,idclear,idmaxmin,idcircle){
	this.idDiv=id;
	this.idLatlong=idl;
	this.idBar=idbar;
	this.idPan=idpan;
	this.idDraw=iddraw;
	this.idPoly=idpoly;
	this.idCircle=idcircle;
	this.idClear=idclear;
	this.idMaxMin=idmaxmin;
	this.barType='full';
	this._curLatLonShape = 0;
	this.curBoton="OFF";
	this.search_map=true;
	this.mini_map=true;
	this._curTool='_pan';
	this._mapTool='';
	this.drawingManager = null;
	this.drawingManagerOpt = {
		drawingControl: false,
		drawingControlOptions: {
		  position: google.maps.ControlPosition.LEFT_CENTER,
		  drawingModes: [
			google.maps.drawing.OverlayType.RECTANGLE,
			google.maps.drawing.OverlayType.POLYGON,
			google.maps.drawing.OverlayType.CIRCLE
		  ]
		},
		circleOptions: {
			fillColor: '#008aff', 
			strokeColor: '#9dc9e2',
			strokeWeight: 3,
			clickable: false
		},
		polygonOptions : {
			fillColor: '#008aff', 
			strokeColor: '#9dc9e2',
			strokeWeight: 3,
			clickable: false
		},
		rectangleOptions: {
			fillColor: '#008aff', 
			strokeColor: '#9dc9e2',
			strokeWeight: 3,
			clickable: false
		}
	};
	this.map = null;
	this.myCurrentShape = null;
	this.filter_map= false;
	this.filter_type= '';
	this.arrayMarkers = [];
	this.lastInfoWin = null;
	
	this.filterONOFF = function (type){
		this.filter_map=!this.filter_map;
		this.filter_type= type;
	}
	
	this.mapFilter =function(mapa){
		var type = this.filter_type;
		Ext.Ajax.request( 
		{  
			waitMsg: 'Filtering...',
			url: 'properties_filter.php', 
			method: 'POST',
			timeout :600000,
			params: { 
				type: type,
				mapa: mapa
			},
			
			failure:function(response,options){
				Ext.MessageBox.alert('Warning','ERROR');
			},
			success:function(response,options){
				if(type=="COMP-MAP") storeComp.load({params:{start:0, limit:limitComp}});
				else if(type=="ACTI-MAP") storeActive.load({params:{start:0, limit:limitActive}});
				else if(type=="DISS-MAP") storeDistress.load({params:{start:0, limit:limitDistress}});
				else if(type=="RENT-MAP") storeRental.load({params:{start:0, limit:limitRental}});
			}                                
		});
	}
	
	this.mouseMove = function (e, self){
		self._curTool = '_pan';
		if(document.getElementById(self.idPan)) document.getElementById(self.idPan).className = 'a_pan';	
		if(document.getElementById(self.idDraw)) document.getElementById(self.idDraw).className = 'd_draw';
		if(document.getElementById(self.idPoly)) document.getElementById(self.idPoly).className = 'd_poly';
		if(document.getElementById(self.idCircle)) document.getElementById(self.idCircle).className = 'd_circle';
		
		if(document.getElementById(self.idMaxMin)){
			if(document.getElementById(self.idMaxMin).className == 'd_maxmin') 
				document.getElementById(self.idMaxMin).className = 'd_maxmin';
			else
				document.getElementById(self.idMaxMin).className = 'a_maxmin';
		}
		
		self.drawingManager.setDrawingMode(null);
	}
	
	this.drawRect = function (e, self){
		if (self.myCurrentShape) self.myCurrentShape.setMap(null);
		self.myCurrentShape = null;
		
		self._curTool = '_draw';
		if(document.getElementById(self.idDraw)) document.getElementById(self.idDraw).className = 'a_draw';
		if(document.getElementById(self.idPan)) document.getElementById(self.idPan).className = 'd_pan';	
		if(document.getElementById(self.idPoly)) document.getElementById(self.idPoly).className = 'd_poly';
		if(document.getElementById(self.idCircle)) document.getElementById(self.idCircle).className = 'd_circle';
		
		if(document.getElementById(self.idMaxMin)){
			if(document.getElementById(self.idMaxMin).className == 'd_maxmin') 
				document.getElementById(self.idMaxMin).className = 'd_maxmin';
			else
				document.getElementById(self.idMaxMin).className = 'a_maxmin';
		}
		
		self.drawingManager.setDrawingMode(google.maps.drawing.OverlayType.RECTANGLE);
	}
	
	this.drawPoly = function (e, self){
		if (self.myCurrentShape) self.myCurrentShape.setMap(null);
		self.myCurrentShape = null;
		
		self._curTool = '_poly';
		if(document.getElementById(self.idDraw)) document.getElementById(self.idDraw).className = 'd_draw';
		if(document.getElementById(self.idPan)) document.getElementById(self.idPan).className = 'd_pan';	
		if(document.getElementById(self.idPoly)) document.getElementById(self.idPoly).className = 'a_poly';
		if(document.getElementById(self.idCircle)) document.getElementById(self.idCircle).className = 'd_circle';
		
		if(document.getElementById(self.idMaxMin)){
			if(document.getElementById(self.idMaxMin).className == 'd_maxmin') 
				document.getElementById(self.idMaxMin).className = 'd_maxmin';
			else
				document.getElementById(self.idMaxMin).className = 'a_maxmin';
		}
		
		self.drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYGON);
	}
	
	this.drawCircle = function (e, self){
		if (self.myCurrentShape) self.myCurrentShape.setMap(null);
		self.myCurrentShape = null;
		
		self._curTool = '_circle';
		if(document.getElementById(self.idDraw)) document.getElementById(self.idDraw).className = 'd_draw';
		if(document.getElementById(self.idPan)) document.getElementById(self.idPan).className = 'd_pan';	
		if(document.getElementById(self.idPoly)) document.getElementById(self.idPoly).className = 'd_poly';
		if(document.getElementById(self.idCircle)) document.getElementById(self.idCircle).className = 'a_circle';
		
		if(document.getElementById(self.idMaxMin)){
			if(document.getElementById(self.idMaxMin).className == 'd_maxmin') 
				document.getElementById(self.idMaxMin).className = 'd_maxmin';
			else
				document.getElementById(self.idMaxMin).className = 'a_maxmin';
		}
		
		self.drawingManager.setDrawingMode(google.maps.drawing.OverlayType.CIRCLE);
	}
	
	this.mapMaxMin = function (e, self){
		self._curTool = '_pan';
		if(document.getElementById(self.idDraw)) document.getElementById(self.idDraw).className = 'd_draw';
		if(document.getElementById(self.idPan)) document.getElementById(self.idPan).className = 'a_pan';	
		if(document.getElementById(self.idPoly)) document.getElementById(self.idPoly).className = 'd_poly';
		if(document.getElementById(self.idCircle)) document.getElementById(self.idCircle).className = 'd_circle';
		
		if(document.getElementById(self.idMaxMin)){
			if(document.getElementById(self.idMaxMin).className == 'd_maxmin'){ 
				document.getElementById(self.idMaxMin).className = 'a_maxmin';
				document.getElementById(self.idDiv).style.height = '600px';
			}else{
				document.getElementById(self.idMaxMin).className = 'd_maxmin';
				document.getElementById(self.idDiv).style.height = '350px';
			}
		}
		
		self.drawingManager.setDrawingMode(null);
		google.maps.event.trigger(self.map, "resize");
	}
	
	this.clearShape = function (e, self){
		self._curTool = '_pan';
		if(document.getElementById(self.idDraw)) document.getElementById(self.idDraw).className = 'd_draw';
		if(document.getElementById(self.idPan)) document.getElementById(self.idPan).className = 'a_pan';	
		if(document.getElementById(self.idPoly)) document.getElementById(self.idPoly).className = 'd_poly';
		if(document.getElementById(self.idCircle)) document.getElementById(self.idCircle).className = 'd_circle';
		
		if(document.getElementById(self.idMaxMin)){
			document.getElementById(self.idMaxMin).className = 'd_maxmin';
			document.getElementById(self.idDiv).style.height = '350px';
			google.maps.event.trigger(self.map, "resize");
		}
		
		if (self.myCurrentShape) self.myCurrentShape.setMap(null);
		self.myCurrentShape = null;
		
		self._curLatLonShape='-1';
		if(document.getElementById(self.idLatlong)) document.getElementById(self.idLatlong).value='-1';
		
		self.drawingManager.setDrawingMode(null);
		
		if(self.filter_map) self.mapFilter('-1');
	}
}

XimaMap.prototype.points2shape = function (vector)
{
	//RECT
	if(vector.length == 2){
		var n = Math.max(vector[0].lat(), vector[1].lat());
		var s = Math.min(vector[0].lat(), vector[1].lat());
		var e = Math.max(vector[0].lng(), vector[1].lng());
		var w = Math.min(vector[0].lng(), vector[1].lng());
		
		this.myCurrentShape = new google.maps.Rectangle({
			fillColor: '#008aff', 
			strokeColor: '#9dc9e2',
			strokeWeight: 3,
			clickable: false,
			map: this.map,
			bounds: {
				north: n,
				south: s,
				east: e,
				west: w
			}
		});
		return true;
	}else if(vector.length == 4){
		var d1 = 0.0, d2 = 0.0, d3 = 0.0;
		
		d1 = (vector[1].lat() - vector[0].lat())*(vector[1].lat() - vector[0].lat()) + (vector[1].lng() - vector[0].lng())*(vector[1].lng() - vector[0].lng());
		d2 = (vector[2].lat() - vector[0].lat())*(vector[2].lat() - vector[0].lat()) + (vector[2].lng() - vector[0].lng())*(vector[2].lng() - vector[0].lng());
		d3 = (vector[3].lat() - vector[0].lat())*(vector[3].lat() - vector[0].lat()) + (vector[3].lng() - vector[0].lng())*(vector[3].lng() - vector[0].lng());
		//CIRCLE
		if(d1 == d2+d3 || d2 == d1+d3 || d3 == d1+d2){ 

			var llb = new google.maps.LatLngBounds();
			llb.extend(vector[0]);
			llb.extend(vector[1]);
			llb.extend(vector[2]);
			llb.extend(vector[3]);
			
			var llc = llb.getCenter();
			var ll = new google.maps.LatLng(llc.lat(), llb.getNorthEast().lng())
			var r = this.getDistanceKM(llc, ll)*1000;
			
			this.myCurrentShape = new google.maps.Circle({
				fillColor: '#008aff', 
				strokeColor: '#9dc9e2',
				strokeWeight: 3,
				clickable: false,
				map: this.map,
				center: llc,
				radius: r
			});
			
			return true;
		}
	}
	//POLY	
	vector.push(vector[0]);
	this.myCurrentShape = new google.maps.Polygon({
		fillColor: '#008aff', 
		strokeColor: '#9dc9e2',
		strokeWeight: 3,
		clickable: false,
		map: this.map,
		paths: vector
	});
	
	return true;
}

XimaMap.prototype.getDistanceKM = function (p1, p2) 
{
    var p1Lat = this.latLonToRadians(p1.lat());
	var p1Lon = this.latLonToRadians(p1.lng());
	
	var p2Lat = this.latLonToRadians(p2.lat());
	var p2Lon = this.latLonToRadians(p2.lng());	
	
	var R = 6371; // earth's mean radius in km
	var dLat  = p2Lat - p1Lat;
	var dLong = p2Lon - p1Lon;
	var a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(p1Lat) * Math.cos(p2Lat) * Math.sin(dLong/2) * Math.sin(dLong/2);
	var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
	var disKm = R * c;
	//var disMiles = disKm * 0.6214;	
	return (disKm);
}
XimaMap.prototype.latLonToRadians = function ( point ) {
	return point * Math.PI / 180;	
}


XimaMap.prototype.ins_toolbar = function (adjust,tipo)
{
	var self = this;
	this._mapTool = document.createElement("div"); 
	var customTool = new CenterControl(this._mapTool, this.map, this);
	
	this._mapTool.id = this.idBar;
	this._mapTool.index = 1;
	this._mapTool.style.padding ="10px 10px 10px 10px"; 
	
	this.map.controls[google.maps.ControlPosition.TOP_LEFT].push(this._mapTool);
	
	this.drawingManager = new google.maps.drawing.DrawingManager(this.drawingManagerOpt);
	this.drawingManager.setMap(this.map);
	google.maps.event.trigger(this.map, "resize");	
	
	google.maps.event.addListener(this.drawingManager, 'rectanglecomplete', function(rect) {
		if (self.myCurrentShape) self.myCurrentShape.setMap(null);		
		self.myCurrentShape = rect;
		
		//self.drawingManager.setDrawingMode(null);
		
		var bounds = rect.getBounds();
		var LL = bounds.getSouthWest();
		var LL2 = bounds.getNorthEast();
		
		var s=LL2.lat()+","+LL2.lng()+"/"+LL.lat()+","+LL.lng();
		self._curLatLonShape=s;
		if(document.getElementById(self.idLatlong)) document.getElementById(self.idLatlong).value=s;
		
		//console.log(s);
		if(self.filter_map) self.mapFilter(s);
		
		
	});
	
	google.maps.event.addListener(this.drawingManager, 'polygoncomplete', function(poly) {
		if (self.myCurrentShape) self.myCurrentShape.setMap(null);
		self.myCurrentShape = poly;
		
		//self.drawingManager.setDrawingMode(null);
		
		var arrayLL = poly.getPath().getArray();
		
		var s="",i=0;
		for(var i=0; i<arrayLL.length; i++){
			if(i>0) s+="/";
			s+=arrayLL[i].toUrlValue();
		}
		self._curLatLonShape=s;
		if(document.getElementById(self.idLatlong)) document.getElementById(self.idLatlong).value=s;
		
		//console.log(s);
		if(self.filter_map) self.mapFilter(s);
	});
	
	google.maps.event.addListener(this.drawingManager, 'circlecomplete', function(circle) {
		if (self.myCurrentShape) self.myCurrentShape.setMap(null);
		self.myCurrentShape = circle;
		
		//self.drawingManager.setDrawingMode(null);
		
		var bounds = circle.getBounds();
		var LL = bounds.getSouthWest();
		var LL2 = bounds.getNorthEast();
		
		var s=LL2.lat()+","+LL.lng()+"/"+LL2.lat()+","+LL2.lng()+"/"+LL.lat()+","+LL2.lng()+"/"+LL.lat()+","+LL.lng();
		self._curLatLonShape=s;
		if(document.getElementById(self.idLatlong)) document.getElementById(self.idLatlong).value=s;
		
		//console.log(s);
		if(self.filter_map) self.mapFilter(s);
	});
}

XimaMap.prototype.DrawPolySaved = function ()
{     
	
	var mapa=document.getElementById(this.idDiv);
	warning.style.display="none";
	this.borrarTodoMap();

	if  (mapa.className!="mapon") {
		mapa.className="mapon"
		this.curBoton="AVG"
		if(this.drawingManager == null) this.ins_toolbar("320px","search");
		this.getCenterPins();
	}

	if ( (this.curBoton=='AVG') && (this._curLatLonShape!=0) ) 
	{
		var _xpoints=new Array();
		for(x=0;x<this._curLatLonShape.split("/").length;x++)
		{								//latitude	longitude
			_xpoints.push(this.getLatLon(this._curLatLonShape.split("/")[x].split(",")[0],this._curLatLonShape.split("/")[x].split(",")[1]));
		}
		this.points2shape(_xpoints)
	}
}

XimaMap.prototype.borrarTodoMap = function (){

	for (var i = 0; i < this.arrayMarkers.length; i++) {
		this.arrayMarkers[i].setMap(null);
	}
	this.arrayMarkers = [];
	
	if (this.myCurrentShape) this.myCurrentShape.setMap(null);
	this.myCurrentShape = null;	
	
	this._curLatLonShape='-1';
	if(document.getElementById(this.idLatlong)) document.getElementById(this.idLatlong).value='-1';
}

XimaMap.prototype._IniMAP = function (lat,lon,mtype)
{
	if (typeof (this.map) != 'undefined' && this.map != null){
		//this.map.dispose();
		this.map = null;
		if(document.getElementById(this.idLatlong)) document.getElementById(this.idLatlong).value='-1';
	}
	
	var mapa = document.getElementById(this.idDiv);

	this.map = new google.maps.Map(
		mapa,
		{
			mapTypeId: (mtype=='birdseye' ? google.maps.MapTypeId.SATELLITE : google.maps.MapTypeId.ROADMAP)
		}
	);
	
	//mapa.style.height = '350px';
	this.borrarTodoMap();
	
	if(typeof(lat)=='undefined') 
		this.centerMapCounty(document.getElementById(search_type+'_county_search').value,true);
	else{ 
		this.map.setCenter(new google.maps.LatLng(lat, lon));
		this.map.setZoom((mtype=='birdseye' ? 19 : 7));
	}
}

XimaMap.prototype._IniMAPOverview = function (latlon,mtype,z)
{
	if (typeof (this.map) != 'undefined' && this.map != null){
		//this.map.dispose();
		this.map = null;
	}
	
	var mapa = document.getElementById(this.idDiv);
	
	this.map = new google.maps.Map(
		mapa,
		{
			mapTypeId: (mtype=='birdseye' ? google.maps.MapTypeId.SATELLITE : google.maps.MapTypeId.ROADMAP),
			center: latlon,
			zoom: (z ? z : 19)
		}
	);
	
	mapa.style.height = '350px';
}

XimaMap.prototype.control_map = function (resete)
{
	var mapa=document.getElementById(this.idDiv);

	if(mapa.style.display=='' || resete==true){
		mapa.style.display='none';
		this.curBoton="OFF"
		this._curTool='_clear';
		this.borrarTodoMap();
	}else{
		mapa.style.display='';
		this.curBoton="AVG";
		if(this.drawingManager == null) this.ins_toolbar("320px","search");
	}     
}

XimaMap.prototype.cleaner = function ()
{
	var mapa=document.getElementById(this.idDiv);

	if(mapa.style.display!='none')
	{
		mapa.style.display='none';
		this.curBoton="OFF"
		this._curTool='_clear';	     
	}
	this.borrarTodoMap();
}

XimaMap.prototype.getCenterPins = function ()
{
	google.maps.event.trigger(this.map, "resize");
	var latlngbounds = new google.maps.LatLngBounds();
	
	for(var i=0; i<this.arrayMarkers.length; i++){
		latlngbounds.extend(this.arrayMarkers[i].getPosition());
	}
	
	if(this.myCurrentShape != null){
		if(this.myCurrentShape instanceof google.maps.Polygon){
			var p = this.myCurrentShape.getPath().getArray();

			for(var i=0; i<p.length; i++){
				latlngbounds.extend(p[i]);
			}
			
		}else{
			latlngbounds.union(this.myCurrentShape.getBounds());
		}
	}
	
	this.map.setCenter(latlngbounds.getCenter());
	this.map.fitBounds(latlngbounds); 
	
	var labels = document.querySelectorAll("[style*='custom-label']")
	for (var i = 0; i < labels.length; i++) {
		// Retrieve the custom labels and rewrite the tag content
		var matches = labels[i].getAttribute('style').match(/custom-label-([a-zA-Z0-9]+)/);
		
		if(matches[1]) labels[i].innerHTML = '<div id="customLabeldiv_'+i+'" class="customLabeldiv">'+matches[1]+'</div>';
	}
}

XimaMap.prototype.centerMapCounty = function (id,press){
	if(press && this.map!=null){
		var esto = this;
		if(id!=-1){
			
			$.ajax({  
				url: '/properties_averageCounty.php',
				type: 'POST',
				data:{ 
					idcounty: id
				},
				
				error:function(response){
					alert('Warning','ERROR');
				},
				
				success: function(response){
					var latlong = response.split('^');
					esto.map.setCenter(new google.maps.LatLng(latlong[0], latlong[1]));
					esto.map.setZoom(9);
					
					if(esto.myCurrentShape!=null) esto.getCenterPins();
				}                                
			});
		}else
			this.map.setCenter(new google.maps.LatLng(29.0, -80.0));
			this.map.setZoom(6);
	}
}
XimaMap.prototype.setBarType = function(type){
	this.barType=type;
}


//PushPins
XimaMap.prototype.getPushpin = function(ind){
	var pin = null;
	
	for(var i=this.arrayMarkers.length-1; i>=0; i--){
		pin = this.arrayMarkers[i]; 
		if (pin instanceof google.maps.Marker){
			if(parseInt(pin.ind)==parseInt(ind)){
				return pin;
			}
		}
	}
	
	return false;
}

XimaMap.prototype.addPushpin = function(lat,lon,urlImg,text){
	var pin = new google.maps.Marker({
		position: new google.maps.LatLng(lat,lon),
		label: text,
		icon: {
			url: urlImg,
			size: new google.maps.Size(21, 21)
		},
		map: this.map,
		ind: -1
	});
	this.arrayMarkers.push(pin);
}

XimaMap.prototype.addPushpinInfobox = function(i, lat, lon, urlImg, a, ga, la, bb, p, s, co, cot){	
	var self = this;
	var data = co.length > 3 ? co.split(',') : new Array();
		
	var infopin = new google.maps.InfoWindow({
		position: new google.maps.LatLng(lat,lon),
		pixelOffset: new google.maps.Size(-12, -2),
		//zIndex: 100,
		content: '<div id="infopin'+i+'" style="background-color: #FFF; background-image: url(\'http://www.reifax.com/img/bkgd_result.jpg\'); border: 3px solid #B8DAE3; min-height:135px; min-width:160px; padding: 5px 8px;"><div class="VE_Pushpin_Popup_Title">'+a+'</div><div class="VE_Pushpin_Popup_Body"><div align="center"><table border="0" style="white-space: nowrap;font-family:\'Trebuchet MS\',Arial,Helvetica,sans-serif;font-size:12px;"><tbody><tr><td rowspan="6" style="overflow:hidden;"><img id="infopinImg'+i+'" src="http://maps.googleapis.com/maps/api/staticmap?center='+lat+','+lon+'&size=60x60&zoom=19&maptype=satellite&key=AIzaSyA4fFt-uXtzzFAgbFpHHOUm7bSXE8fvCb4" height="105px" width="135px;"></td></tr><tr><td style=" font-weight:bold; margin-right:5px;">Gross Area:</td><td>'+ga+'</td></tr><tr><td style=" font-weight:bold; margin-right:5px;">Living Area:</td><td>'+la+'</td></tr><tr><td style=" font-weight:bold; margin-right:5px;">Beds/Baths:</td><td>'+bb+'</td></tr><tr><td style=" font-weight:bold; margin-right:5px;">Price</td><td>'+p+'</td></tr><tr><td style=" font-weight:bold; margin-right:5px;">Status:</td><td>'+s+'</td></tr><tr><td onclick="'+co+'" style="font-weight:bold; margin-right:5px;cursor:pointer;color:blue;font-size:13px;" colspan="2">'+cot+'</td></tr></tbody></table></div></div></div>' 
	});

	if(s == 'Subject' || i.length == 0){
		var pin = new google.maps.Marker({
			position: new google.maps.LatLng(lat,lon),
			icon: {
				url: urlImg,
				size: new google.maps.Size(21, 21)
			},
			map: this.map,
			infowin: infopin,
			ind: i
		});
	}else{
		var pin = new google.maps.Marker({
			position: new google.maps.LatLng(lat,lon),
			label: {
				text: i,
				fontFamily: i ? 'Roboto, Arial, sans-serif, custom-label-' + i : ''
			},
			icon: {
				url: urlImg,
				size: new google.maps.Size(21, 21)
			},
			map: this.map,
			infowin: infopin,
			ind: i
		});
	}
	this.arrayMarkers.push(pin);
	
	google.maps.event.addListener(pin, 'click', function() {
		if(self.lastInfoWin) self.lastInfoWin.close();
		
		infopin.open(self.map, pin);
		self.lastInfoWin = infopin;
	});
	
	if(data.length > 1){
		google.maps.event.addListener(infopin, 'domready', function(){
			setFirstImage(data[1],'infopinImg'+pin.ind,(data[0]).replace('createOverview(',''));
		});
	}
	
	google.maps.event.addListener(this.map, 'idle', function() {
		var labels = document.querySelectorAll("[style*='custom-label']")
		for (var i = 0; i < labels.length; i++) {
			// Retrieve the custom labels and rewrite the tag content
			var matches = labels[i].getAttribute('style').match(/custom-label-([0-9]+)/);
			
			if(matches[1]) labels[i].innerHTML = '<div id="customLabeldiv_'+i+'" class="customLabeldiv">'+matches[1]+'</div>';
		}
	});
	
	
	return pin;
}

XimaMap.prototype.addPushpinInfoboxImage = function(i, lat, lon, urlImg, a, ga, la, bb, p, s, co, cot, urlImgC){	
	var infopin = new google.maps.InfoWindow({
		position: new google.maps.LatLng(lat,lon),
		pixelOffset: new google.maps.Size(-12, -2),
		//zIndex: 100,
		content: '<div id="infopin'+i+'" style="background-color: #FFF; background-image: url(\'http://www.reifax.com/img/bkgd_result.jpg\'); border: 3px solid #B8DAE3; min-height:135px; min-width:160px; padding: 5px 8px;"><div class="VE_Pushpin_Popup_Title">'+a+'</div><div class="VE_Pushpin_Popup_Body"><div align="center"><table border="0" style="white-space: nowrap;font-family:\'Trebuchet MS\',Arial,Helvetica,sans-serif;font-size:12px;"><tbody><tr><td rowspan="6" colspan="2" style="overflow:hidden;"><img src="'+urlImgC+'" height="105px" width="135px;"></td></tr><tr><td style=" font-weight:bold; margin-right:5px;">Gross Area:</td><td>'+ga+'</td></tr><tr><td style=" font-weight:bold; margin-right:5px;">Living Area:</td><td>'+la+'</td></tr><tr><td style=" font-weight:bold; margin-right:5px;">Beds/Baths:</td><td>'+bb+'</td></tr><tr><td style=" font-weight:bold; margin-right:5px;">Price</td><td>'+p+'</td></tr><tr><td style=" font-weight:bold; margin-right:5px;">Status:</td><td>'+s+'</td></tr><tr><td onclick="'+co+'" style="font-weight:bold; margin-right:5px;cursor:pointer;color:blue;font-size:13px;" colspan="2">'+cot+'</td></tr></tbody></table></div></div></div>'
	});
	
	if(s == 'Subject' || i.length == 0){
		var pin = new google.maps.Marker({
			position: new google.maps.LatLng(lat,lon),
			icon: {
				url: urlImg,
				size: new google.maps.Size(21, 21)
			},
			map: this.map,
			infowin: infopin,
			ind: i
		});
	}else{
		var pin = new google.maps.Marker({
			position: new google.maps.LatLng(lat,lon),
			label: {
				text: i,
				fontFamily: i ? 'Roboto, Arial, sans-serif, custom-label-' + i : ''
			},
			icon: {
				url: urlImg,
				size: new google.maps.Size(21, 21)
			},
			map: this.map,
			infowin: infopin,
			ind: i
		});
	}
	this.arrayMarkers.push(pin);

	google.maps.event.addListener(pin, 'click', function() {
		if(self.lastInfoWin) self.lastInfoWin.close();
		
		infopin.open(self.map, pin);
		self.lastInfoWin = infopin;
	});
	google.maps.event.addListener(this.map, 'idle', function() {
		var labels = document.querySelectorAll("[style*='custom-label']")
		for (var i = 0; i < labels.length; i++) {
			// Retrieve the custom labels and rewrite the tag content
			var matches = labels[i].getAttribute('style').match(/custom-label-([0-9]+)/);
			
			if(matches[1]) labels[i].innerHTML = '<div id="customLabeldiv_'+i+'" class="customLabeldiv">'+matches[1]+'</div>';
		}
	});
	
	return pin;
}

XimaMap.prototype.addPushpinInfoboxMini = function(i, lat, lon,a,p,be,ba,sqft, s,url,pointColor,pid,bd,urlImg){	
	var price=abbreviaNumber(p);
	var self = this;
	
    if(!urlImg) urlImg = 'http://maps.googleapis.com/maps/api/staticmap?center='+lat+','+lon+'&size=80x80&zoom=19&maptype=satellite&key=AIzaSyA4fFt-uXtzzFAgbFpHHOUm7bSXE8fvCb4';
    
	var infopin = new google.maps.InfoWindow({
		position: new google.maps.LatLng(lat,lon),

		content: '<div class="arrow_box"><h5>'+a+'</h5><div id="miniMapInfobox'+i+'" style="height:60px; margin-left:10px; position:relative; float:left; background:#ccc;"><img height="80px" id="miniMapInfoboxImg'+i+'" src="'+urlImg+'"></div><div style="float:left;width:124px;margin-left:10px;"><p>'+s+'</p>'+((parseInt(p)!=0)?'<p>'+formatNumber.new(parseInt(p), "$")+'</p>':'')+'<p>'+be+' Beds/ '+ba+' Baths<p>'+sqft+' sqft</p></div><div class="clearEmpty"></div><div style="text-align:center;">'+((url!=null)?'<a href="'+url+'">View Property</a>':'')+'</div></div>'
	});
	
	var pin = new google.maps.Marker({
		position: new google.maps.LatLng(lat,lon),
		label: { 
			text: '_',
			fontFamily: 'Roboto, Arial, sans-serif, custom-label-' + price
		},
		icon: {
			path: google.maps.SymbolPath.CIRCLE,
			fillColor: pointColor.color,
			fillOpacity: 1,
			strokeColor: pointColor.border,
			strokeWeight: 2,
			scale: 6,
			labelOrigin: new google.maps.Point(0,2)
		},
		map: this.map,
		infowin: infopin,
		ind: i
	});
	this.arrayMarkers.push(pin);	

	google.maps.event.addListener(pin, 'click', function() {
		if(self.lastInfoWin) self.lastInfoWin.close();
		
		infopin.open(self.map, pin);
		self.lastInfoWin = infopin;
	});
	
	if(pid){
		google.maps.event.addListener(infopin, 'domready', function(){
			setFirstImage(pid,'miniMapInfoboxImg'+pin.ind,bd);
		});
	}
	
	google.maps.event.addListener(this.map, 'idle', function() {
		var labels = document.querySelectorAll("[style*='custom-label']")
		for (var i = 0; i < labels.length; i++) {
			// Retrieve the custom labels and rewrite the tag content
			var matches = labels[i].getAttribute('style').match(/custom-label-([a-zA-Z0-9]+)/);
			
			if(matches[1]) labels[i].innerHTML = '<div id="customLabeldiv_'+i+'" class="customLabeldiv">$'+matches[1]+'</div>';
		}
	});
	
	return pin;
}
XimaMap.prototype.pinMouseOver=function (pin){
	if(this.lastInfoWin) this.lastInfoWin.close();
		
	pin.infowin.open(this.map, pin);
	this.lastInfoWin = pin.infowin;
}

function setFirstImage(pid,imgID,bdcounty){
	//console.log(imgID);
	$.ajax({  
		url: '/properties_setFirstImage.php',
		type: 'POST',
		data:{ 
			pid: pid,
			bd: bdcounty
		},
		
		error:function(response){
			alert('Warning','ERROR');
		},
		
		success: function(response){
			var results=response;
			if(results != 'N'){
				if(document.getElementById(imgID)) 
					document.getElementById(imgID).src=results;
			}
		}                                
	});
}

function abbreviaNumber($numero) {
	var $abreviaturas = new Array('K', 'M', 'B', 'mB', 'mB');
	var $ultima_abreviatura = $abreviaturas.length-1;
	var $divisor = 1000;
	var $sufijo = -1;
	
	while ($numero > $divisor && $sufijo < $ultima_abreviatura) {
		 $numero /= $divisor;
		 $sufijo++;
	}
	
	return parseInt($numero)+($sufijo>-1?$abreviaturas[$sufijo]:'');
}
var formatNumber = {
	separador: ",", // separador para los miles
	sepDecimal: '.', // separador para los decimales
	formatear:function (num){
		num +='';
		var splitStr = num.split('.');
		var splitLeft = splitStr[0];
		var splitRight = splitStr.length > 1 ? this.sepDecimal + splitStr[1] : '';
		var regx = /(\d+)(\d{3})/;
		while (regx.test(splitLeft)) {
			splitLeft = splitLeft.replace(regx, '$1' + this.separador + '$2');
		}
		return this.simbol + splitLeft  +splitRight;
	},
	new:function(num, simbol){
		this.simbol = simbol ||'';
		return this.formatear(num);
	}
}