requirejs.config({
    appDir: ".",
    baseUrl: "/js",
    paths: { 
        /* Load jquery from google cdn. On fail, load local file. */
        'jquery': ['//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min', 'jquery'],
        /* Load bootstrap from cdn. On fail, load local file. */
        'bootstrap': ['//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min']
    },
    shim: {
        /* Set bootstrap dependencies (just jQuery) */
        'bootstrap' : ['jquery']
    }
});

require(["jquery","map", 'bootstrap'], function($,Map){
    map = new Map("demoCanvas");
    map.loadBG("/img/board2.jpg");
    map.initialise();
    $('#myModal button.btn-primary').click(function(e){
        $('#myModal').modal('hide');
        var selectedUnits = [];
        var request = $('input[name="regionOptions"]:checked').val();
        var region = $('input[name="regionID"]').val();
        $('input[name="selectedUnits"]:checked:not(:disabled)').each( function() {
            selectedUnits.push($(this).val());
        })
        var casualty = 0;
        if ( $('input[name="casualty"]:checked' ).val() == 1 ) {
            casualty = 1;
        }
        if ( request == 'move' ) {
            alert('Select the region to move the units to');
            map.moveSelect(selectedUnits);
        } else {
            $.post(
                'ajaxLink.php',
                { request: request,
                unit: $('select[name="recruit"]').val(),
                selectedUnits: selectedUnits,
                casualty: casualty,
                region: region },
                function(data) {
                    alert(data);
                    map.refreshUnits();
                }
            );
        }
        
        
    });
    $( "input:radio[name=regionOptions]" ).change( function() {
		toggleOption( this );
	});
    $(".toggle").hide();
    $('#myModal').on('hidden.bs.modal', function() {
       $( "input:radio[name=regionOptions]").prop('checked',false);
       if ( prevRad.length > 0 ) {
		$( "." + prevRad + "Options" ).hide();
		$( "." + prevRad + "Options *" ).prop( 'disabled', true );
        $( "." + prevRad + "Options *" ).prop( 'checked', false );
        }
    });
    var audioPath = "/music/";
    var manifest = [
        {id:"Music", src:"02 - Concerning Hobbits.mp3"}
    ];
    if (!createjs.Sound.initializeDefaultPlugins()) {return;}
    createjs.Sound.addEventListener("fileload", handleLoad);
    createjs.Sound.registerManifest(manifest, audioPath);
});

var prevRad = '';
var gameID = getGameID();

var toggleOption = function( that ) {
	if ( prevRad.length > 0 ) {
		$( "." + prevRad + "Options" ).hide();
		$( "." + prevRad + "Options *" ).prop( 'disabled', true );
        $( "." + prevRad + "Options *" ).prop( 'checked', false );
	}
	$( "." + $( that ).val() + "Options" ).show();
	$( "." + $( that ).val() + "Options *" ).prop( 'disabled', false );
    $( "." + $( that ).val() + "Options *" ).prop( 'checked', false );
	prevRad = $( that ).val();
};

function handleLoad(event) {
    createjs.Sound.play(event.src);
}

function getGameID() {
    var str = window.location.href;
    var n = str.lastIndexOf('/');
    var result = str.substring(n + 1);
    return result;
}