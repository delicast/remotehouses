/**
 * Created by David on 03-Jul-16.
 */
$('#start_digitizing').addClass("active")

var startDrawing=false;




var housesstyles = [
    new ol.style.Style({
        image: new ol.style.Circle({
            radius: 6,
            stroke: new ol.style.Stroke({
                color: 'white',
                width: 2
            }),
            fill: new ol.style.Fill({
                color: 'Chartreuse'
            })
        })

    })
];
var styles = [
    new ol.style.Style({
        stroke: new ol.style.Stroke({
            color: 'black',
            width: 3,
            lineDash:[5]
        })
    })
];

var housesdone=new ol.layer.Vector({

    source: new ol.source.Vector({

        url: '/geometry/get_houses_project/'+project_id,
        format: new ol.format.GeoJSON({

            defaultDataProjection :'EPSG:3857',
            projection: 'EPSG:3857'
        })
    }),
    name: 'housesdone',
    style: housesstyles
});
var cell=new ol.layer.Vector({

    source: new ol.source.Vector({

        url: '/geometry/get_cell_json/'+grid_id,
        format: new ol.format.GeoJSON({
            
            defaultDataProjection :'EPSG:3857',
            projection: 'EPSG:3857'
        })
    }),
    name: 'cell',
    style: styles
});


var points = new ol.Collection();
var source_points = new ol.source.Vector({
    features: points
});
var featureOverlay = new ol.layer.Vector({
    source: source_points,
    style: function(feature, resolution){
        var styleHouse = new ol.style.Style( {
            fill: new ol.style.Fill({
                color: 'rgba(255, 255, 255, 0.2)'
            }),
            stroke: new ol.style.Stroke({
                color: '#ffcc33',
                width: 2
            }),
            image: new ol.style.Circle({
                radius: 7,
                fill: new ol.style.Fill({
                    color: '#ffcc33'
                })
            })
        } );

        var styleWaterhole = new ol.style.Style( {
            fill: new ol.style.Fill({
                color: 'rgba(255, 255, 255, 0.2)'
            }),
            stroke: new ol.style.Stroke({
                color: '#000077',
                width: 2
            }),
            image: new ol.style.Circle({
                radius: 7,
                fill: new ol.style.Fill({
                    color: '#000077'
                })
            })
        } );

        if ( feature.get('type') == 'House') {
            return [styleHouse];
        } else {
            return [styleWaterhole];
        }
    }
});



var mousePositionControl = new ol.control.MousePosition({
    coordinateFormat: ol.coordinate.toStringHDMS,  //toStringXY
    projection: 'EPSG:4326',  //3857
    // comment the following two lines to have the mouse position
    // be placed within the map.
    className: 'custom-mouse-position',
    target: document.getElementById('mouse-position'),
    undefinedHTML: '&nbsp;'
});

var view = new ol.View({
    zoom: 15,  //17 para grid pequena
    maxZoom:19,
    minZoom: 14, //17 para grid pequena
    center: [2,2] //random, depois se actualza com o getView().fit
});

var map1 = new ol.Map({
    layers: [
        new ol.layer.Tile({
            preload: Infinity,
            source: new ol.source.BingMaps({
                key: 'At4LG_GUfiXz_wezlE0vG6ZcrOZhoxPa2xK8nlLBlX1e4KbJGx5biSGRuph2RYmS',
                imagerySet: 'AerialWithLabels'
            })
        })
        ,cell,housesdone,featureOverlay],
    target: 'map',
    controls:[mousePositionControl],
    view: view
});

cell.getSource().on('change',function(e){
    if(cell.getSource().getState() === 'ready') {
        map1.getView().fit(cell.getSource().getExtent(), map1.getSize());
        map1.getView().setProperties(
            {'extent':cell.getSource().getExtent()});  // Isto devia bloquear a vista para nao poder sair do extent, mas nao funciona
    }
});


var draw ; // global so we can remove it later
var singleClick;

var teste;
var teste2;
var out=false;
var outfeat;

//Quando coloco removeFeature no evento drawend do interaction, fica apanhado o mapa, por isso encontrei esta alternativa

$("#map").click(function(){
    if (out){
        source_points.removeFeature(outfeat);
    }
});



var modify = new ol.interaction.Modify({
    features: points
});
map1.addInteraction(modify);

/* NAO ESTA  A FUNCIONAR BEM... REVER!
 modify.on('modifyend', function(e) {
 teste=e;
 var point_x= e.features.getArray()[0].getGeometry().getCoordinates()[0];
 var point_y= e.features.getArray()[0].getGeometry().getCoordinates()[1];

 if (point_y > maxlat_t || point_y < minlat_t || point_x < minlon_t || point_x > maxlon_t ){
 alert("Modification out of the digitization area!");
 out=true;
 outfeat= e.feature;
 }else{
 out=false;
 }
 });
 */




function recenterMap(){
    map1.getView().fit(cell.getSource().getExtent(), map1.getSize());
}
function addHouseholds() {

    map1.removeInteraction(singleClick);
    map1.removeInteraction(draw);
    draw = new ol.interaction.Draw({
        features: points,
        type: 'Point'
    });
    map1.addInteraction(draw);
    draw.on('drawend', function(e) {
        var point_x=e.feature.getGeometry().getCoordinates()[0];
        var point_y=e.feature.getGeometry().getCoordinates()[1];
        e.feature.setProperties({
            'type': 'House'
        })
        //  console.info(e.feature.getGeometry().getCoordinates());
        /*if (point_y > maxlat_t || point_y < minlat_t || point_x < minlon_t || point_x > maxlon_t ){
            alert("Out of the digitization area!");
            out=true;
            outfeat= e.feature;
        }else{
            out=false;
        }
        */
        out=false; //delete
    });


    map1.addInteraction(modify);
    $('#addHouseholds').addClass('btn-pressed');
    $('#removeHouseholds').removeClass('btn-pressed')
    $('#addWaterhole').removeClass('btn-pressed')
}

function addWaterhole() {

    map1.removeInteraction(singleClick);
    map1.removeInteraction(draw);
    draw = new ol.interaction.Draw({
        features: points,
        type: 'Point'
    });
    map1.addInteraction(draw);
    draw.on('drawend', function(e) {
        var point_x=e.feature.getGeometry().getCoordinates()[0];
        var point_y=e.feature.getGeometry().getCoordinates()[1];
        e.feature.setProperties({
            'type': 'Waterhole'
        })
        //  console.info(e.feature.getGeometry().getCoordinates());
        /*if (point_y > maxlat_t || point_y < minlat_t || point_x < minlon_t || point_x > maxlon_t ){
         alert("Out of the digitization area!");
         out=true;
         outfeat= e.feature;
         }else{
         out=false;
         }
         */
        out=false; //delete
    });


    map1.addInteraction(modify);
    $('#addWaterhole').addClass('btn-pressed');
    $('#removeHouseholds').removeClass('btn-pressed')
    $('#addHouseholds').removeClass('btn-pressed');
}

function removeHouseholds(){

    map1.removeInteraction(modify);
    map1.removeInteraction(draw);

    singleClick = new ol.interaction.Select({
        layers: [featureOverlay]
    });


    $('#addHouseholds').removeClass('btn-pressed');
    $('#addWaterhole').removeClass('btn-pressed');
    $('#removeHouseholds').addClass('btn-pressed');

    map1.addInteraction(singleClick);
    console.log("222");

    singleClick.on('select', function(evt){
        var coord = evt.mapBrowserEvent.coordinate;

        if(delete_feat=singleClick.getFeatures().getArray()[0]){
            source_points.removeFeature(delete_feat);
            singleClick.getFeatures().clear();
        }
    });
}
function create_confirmation(){
    if (source_points.getFeatures().length==0){
        $('#modal-title')[0].innerHTML="Are you sure the Image has no Features?";
        $('#modal-body')[0].innerHTML=
            "<p>Why are you leaving this image empty?</p> " +
            "<div class='radio' style='text-align: left'>" +
            "<label><input type='radio' name='reason_void' value='no_houses' checked >There are no households on the select area of the image</label>"+
            "</div>"+
            "<div class='radio' style='text-align: left'>"+
            "<label><input type='radio' name='reason_void' value='bad_quality' >Problems with the image (clouds, bad quality, etc..)</label>"+
            "</div>";
    }
    else{
        $('#modal-title')[0].innerHTML="Thank you! Please confirm";
        $('#modal-body')[0].innerHTML=
            "<p>You have register <b>" + source_points.getFeatures().length + "</b> Features!</p> ";
    }
}

var metadata_msg=0;
function getMetadata_house(coordinate, zoom_level,i){
//Coordinate is Latitude (North, south),Longitude(East,West). Ex: -12.55,15.23

    bing_key = "At4LG_GUfiXz_wezlE0vG6ZcrOZhoxPa2xK8nlLBlX1e4KbJGx5biSGRuph2RYmS";
    imagery = "Aerial";
    metadata_request_url = "http://dev.virtualearth.net/REST/V1/Imagery/Metadata/{0}/{1}?zl={2}&o=json&key={3}";
    metadata_request_url = metadata_request_url.replace("{0}",imagery)
    metadata_request_url = metadata_request_url.replace("{1}",coordinate)
    metadata_request_url = metadata_request_url.replace("{2}",zoom_level)
    metadata_request_url = metadata_request_url.replace("{3}",bing_key)

    $.ajax({url: metadata_request_url,
        success: function(result){
            console.log(metadata_request_url)
            console.log(result)

            tile_properties = result["resourceSets"][0]["resources"][0];
            console.log(tile_properties.vintageEnd, tile_properties.vintageStart);

            metadata_msg=[tile_properties.vintageStart, tile_properties.vintageEnd];

            $('#inputs_houses').append("<input type='hidden' name='house["+i+"][s]' value="+ metadata_msg[0]+" >");
            $('#inputs_houses').append("<input type='hidden' name='house["+i+"][e]' value="+ metadata_msg[1]+" >");

        },
        error: function(){
            console.log("error")
            metadata_msg=0;
            $('#inputs_houses').append("<input type='hidden' name='house["+i+"][s]' value="+ null+" >");
            $('#inputs_houses').append("<input type='hidden' name='house["+i+"][e]' value="+ null+" >");


        }});

}



function array_points_form (){
    var i=0;
    source_points.forEachFeatureIntersectingExtent(cell.getSource().getExtent(), function(feature){
        teste=feature;
        if (feature.getProperties().type=='House'){
            var coord=feature.getGeometry().getCoordinates();

            var coord_WGS84= ol.proj.transform(coord, 'EPSG:3857', 'EPSG:4326');

            //getMetadata_house(coord_WGS84[1]+","+coord_WGS84[0],map1.getView().getZoom(),i);

            $('#inputs_houses').append("<input type='hidden' name='house["+i+"][x]' value="+ coord_WGS84[0]+" >");
            $('#inputs_houses').append("<input type='hidden' name='house["+i+"][y]' value="+ coord_WGS84[1]+" >");

            i++; 
        }
        if (feature.getProperties().type=='Waterhole'){
            var coord=feature.getGeometry().getCoordinates();

            var coord_WGS84= ol.proj.transform(coord, 'EPSG:3857', 'EPSG:4326');

            //getMetadata_house(coord_WGS84[1]+","+coord_WGS84[0],map1.getView().getZoom(),i);

            $('#inputs_houses').append("<input type='hidden' name='waterhole["+i+"][x]' value="+ coord_WGS84[0]+" >");
            $('#inputs_houses').append("<input type='hidden' name='waterhole["+i+"][y]' value="+ coord_WGS84[1]+" >");

            i++;
        }

    })
}


