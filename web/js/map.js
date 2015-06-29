define(['createjs','region'], function(cjs,Region){
    function extendShape() {
        createjs.Shape.prototype.setFillColour = function( colour ) {
            this.fillColour = undefined;
            this.fillColour = colour;
            return this;
        };
        createjs.Shape.prototype.drawPolygon = function( coords ) {
            if ( arguments.length == 1 ) {
                this.coords = arguments[0];
            }
            if ( typeof this.coords == 'object' ) {
                if ( typeof this.fillColour == 'undefined' ) {
                    this.fillColour = 'black';
                }
                this.graphics.beginStroke(this.fillColour).beginFill(this.fillColour);
                for( var i = 0; i < this.coords.length; i = i + 2 ) {
                    if ( i == 0 ) {
                        this.graphics.moveTo(this.coords[i],this.coords[i+1]);
                    } else {
                        this.graphics.lineTo(this.coords[i],this.coords[i+1]);
                    }
                }
                this.graphics.endFill();
            } else {
                alert( 'Cannot draw' );
            }
        };
    };
    function addHandlers( targetObj, self ) {
        targetObj.on("mouseover", function(evt) {
            evt.target.select();
            self.stage.canvas.title = evt.target.text;
            self.refreshMap();
        });
        targetObj.on("mouseout", function(evt) {
            evt.target.selectable();
            self.stage.canvas.title = '';
            self.refreshMap();
        });
        targetObj.on("click", function(evt) {
            if ( evt.target.unselectable !== 1 ) {
                $('#myModal h4.modal-title').text(evt.target.text);
                $('#myModal input[name="regionID"]').val(evt.target.name);
                $('#myModal .modal-body div.removeUnits').html('');
                $('#myModal .modal-body div.moveUnits').html('');
                $('#myModal .modal-body div.reduceElite').html('');
                units = '';
                $.each(evt.target.units, function(k,v) {
                    var cbUnit = '<div class="checkbox"><label>';
                    cbUnit += '<input type="checkbox" name="selectedUnits" value="';
                    cbUnit += v.id + '">';
                    if ( typeof(v.name)=='string' ) {
                        cbUnit += v.name;
                        units += v.name + "<br/>";
                    } else {
                        cbUnit += v.nation + " " + v.type;
                        units += v.nation + " " + v.type + "<br/>";
                    }
                    cbUnit += '</label></div>';
                    $('#myModal .modal-body div.removeUnits').append(cbUnit);
                    $('#myModal .modal-body div.moveUnits').append(cbUnit);
                    if ( v.type == 'Elite' ) $('#myModal .modal-body div.reduceElite').append(cbUnit);
                });
                if ( units == '' ) units = 'No units';
                $('#myModal .modal-body .units').html(units);
                $('#myModal').modal('show');
                evt.target.graphics.clear();
                evt.target.drawPolygon();
                self.refreshMap();
            }   
        });
    }
    function Map(canvasID) {
        this.stage = new createjs.Stage(canvasID);
        this.stage.enableMouseOver(20);
        extendShape();
        this.loadBG = function(bgSrc) {
            this.bgImage = new createjs.Bitmap(bgSrc);
            this.bg = new createjs.Container();
            this.bg.x = this.bg.y = 0;
            this.bg.addChild(this.bgImage);
            this.stage.addChild(this.bg);
            // Refresh Map when image loaded - bind used, could have used closure
            // and self = this
            this.bgImage.image.onload = this.refreshMap.bind(this);
        }   
        
        this.initialise = function() {
            var self = this;
            $.post(
                '/wotr/ajax/regions/' + gameID,
                { request: 'regions' },
                function(data) {
                    self.addRegions(data.data);
                    self.refreshMap();
                    self.refreshUnits();
                },
                'json'
            );
        }
        
        this.refreshMap = function() {
            this.stage.update();
        }
 
        this.addRegions = function( data ) {
            this.stage.removeChild(this.stage.getChildByName('regions'));
            regions = new createjs.Container();            
            regions.name = 'regions';
            var self = this;
            $.each( data, function (k, v) {
                var str_arr = v.coords.split(',');               
                newRegion = new Region(k,v);
                newRegion.addNation( v.nation );
                newRegion.selectable();               
                addHandlers( newRegion, self );        
                newRegion.drawPolygon(str_arr);
                regions.addChild(newRegion);
            });
            this.stage.addChild(regions);
            this.regions = regions;//this.stage.getChildByName('regions');
        }
        
        this.addUnits = function( data ) {
            $('#recruit').find('option:not(".keep")').remove();
            //$.post(
            //    'ajaxLink.php',
            //    { request: 'recruitable' },
            //    function(data){
            //        $.each(data, function(k,v){
            //            $('#recruit').append($('<option/>', {
            //                value: k,
            //                text: v
            //            }));
            //        });
            //    },
            //    'json'
            //)
            
            regions = this.regions;
            $.each( data, function (k, v) {
                regions.getChildByName(v.loc).units.push(
                        {id:k,
                        name:v.unitName,
                        nation:v.nation,
                        type:v.type,
                        side:parseInt(v.sideId)});
            });
            units = new createjs.Container();
            units.name = 'units';
            this.stage.removeChild(this.stage.getChildByName('units'));
            for( i = 0; i < this.regions.getNumChildren(); i++ ) {
                var region = this.regions.getChildAt(i);
                if ( region.units.length !== 0 ) {
                    $.each( region.units, function(k,v){
                        bm = false;
                        switch( v.side ) {
                            case 1:
                                if ( v.type == "Character" || v.type == "Fellowship" ) {
                                    bm = "/img/fpchar.png";
                                } else {
                                    bm = "/img/fpunit.png";
                                }
                                break;
                            case 2:
                                if ( v.type == "Character" || v.type == "Fellowship" ) {
                                    bm = "/img/schar.png";
                                } else {
                                    bm = "/img/sunit.png";
                                }
                                break;
                            default:
                                bm = "/img/sunit.png";
                                break;
                        }
                        if ( bm ) {
                            unit = new createjs.Bitmap(bm);
                        
                            var pos = region.unitPos.split(',');
                            unit.x = pos[0];
                            unit.y = pos[1];
                            units.addChild(unit);
                        }
                    });                   
                }
            }
            this.stage.addChild(units);
        }
        
        this.refreshUnits = function() {
            for( i = 0; i < this.regions.getNumChildren(); i++ ) {
                var region = this.regions.getChildAt(i);
                region.units = [];
            }
            var self = this;
            $.post(
                '/wotr/ajax/units/' + gameID,
                { request: 'units' },
                function(data) {
                    self.addUnits(data.data);
                    self.refreshMap();
                },
                'json'
            );
        }
        
        this.selectRegion = function( regionID ) {
            this.regions.getChildByName(regionID).select();
        }
        
        this.unselectableRegion = function( regionID ) {
            this.regions.getChildByName(regionID).unselectable();
        }
        
        this.canRecruit = function() {
            for( i = 0; i < this.regions.getNumChildren(); i++ ) {
                var region = this.regions.getChildAt(i);
                if ( !(region.town || region.stronghold || region.city ) ) {
                    region.unselectable();
                    region.graphics.clear();
                    region.drawPolygon();
                }
            }
        }
        
        this.moveSelect = function(sU) {
            var self = this;
            for( i = 0; i < this.regions.getNumChildren(); i++ ) {
                var region = this.regions.getChildAt(i);
                region.removeAllEventListeners('click');
                region.on("click", function(evt) {
                    $.post(
                        'ajaxLink.php',
                        { request: 'move',
                            selectedUnits: sU,
                            dest: evt.target.name },
                        function( data ) {
                            alert( data );
                            self.initialise();
                        }
                    );
                });
            }
        }
    };
    return Map;
});
////var stage, circle, square, regions, units;
//        function addHandlers( targetObj ) {
//            targetObj.on("mouseover", function(evt) {
//                evt.target.select();
//                stage.canvas.title = evt.target.text;
//                stage.update();
//            });
//            targetObj.on("mouseout", function(evt) {
//                evt.target.deselect();
//                stage.canvas.title = '';
//                stage.update();
//            });
//            targetObj.on("click", function(evt) {
//                evt.target.graphics.clear();
//                evt.target.drawPolygon();
//                stage.update();
//            });
//        }
//        
//        function handleLoad(event) {
//            createjs.Sound.play(event.src);
//        }
//        function init() {
//            var audioPath = "music/";
//            var manifest = [
//                {id:"Music", src:"02 - Concerning Hobbits.mp3"}
//            ];
////            if (!createjs.Sound.initializeDefaultPlugins()) {return;}
//            createjs.Sound.addEventListener("fileload", handleLoad);
//            createjs.Sound.registerManifest(manifest, audioPath);
//        }
//        function init2() {
//            stage = new createjs.Stage("demoCanvas");
//            createjs.Touch.enable(stage, true, true);
//            stage.mouseMoveOutside = true;
//            var temp = new createjs.Bitmap("img/board2.jpg");
//            var background = new createjs.Container();
//            temp.image.onload = function() {                 
//                stage.update(); }
//            background.x = background.y = 0;
//            background.addChild(temp);
//            stage.addChild(background);
//            stage.enableMouseOver(20);
//            regions = new createjs.Container();
//            
//            createjs.Shape.prototype.setFillColour = function( colour ) {
//                this.fillColour = colour;
//                return this;
//            };
//            createjs.Shape.prototype.drawPolygon = function( coords ) {
//                if ( arguments.length == 1 ) {
//                    this.coords = arguments[0];
//                }
//                if ( typeof this.coords == 'object' ) {
//                    if ( typeof this.fillColour == 'undefined' ) {
//                        this.fillColour = 'black';
//                    }
//                    this.graphics.beginStroke(this.fillColour).beginFill(this.fillColour);
//                    for( var i = 0; i < this.coords.length; i = i + 2 ) {
//                        if ( i == 0 ) {
//                            this.graphics.moveTo(this.coords[i],this.coords[i+1]);
//                        } else {
//                            this.graphics.lineTo(this.coords[i],this.coords[i+1]);
//                        }
//                    }
//                    this.graphics.endFill();
//                } else {
//                    alert( 'Cannot draw' );
//                }
//            };
//            function Region() {
//                createjs.Shape.call(this);
//            }
//            Region.prototype = Object.create(createjs.Shape.prototype);
//            Region.prototype.constructor = Region;
//            Region.prototype.addNation = function( nation_id ) {
//                colourArr = {
//                    '0':'black',
//                    '1':'saddlebrown',
//                    '2':'limegreen',
//                    '3':'lightskyblue',
//                    '4':'green',
//                    '5':'blue',
//                    '6':'red',
//                    '7':'orange',
//                    '8':'yellow' };
//                this.setFillColour( colourArr[nation_id] );
//            };
//            Region.prototype.block = function() {
//                this.fillColour = 'black';
//                this.alpha = 0.8;
//                this.block = 1;
//            }
//            Region.prototype.select = function() {
//                if ( this.block !== 1 ) {
//                    this.selected = 1;
//                    this.alpha = 0.5;
//                }
//            };
//            Region.prototype.deselect = function() {
//                if ( this.block !== 1 ) {
//                    this.selected = 0;
//                    this.alpha = 0.1;
//                }
//            };
//            $.getJSON(
//                'ajaxLink.php',
//                function(data) {
//                    $.each( data, function (k, v) {
//                        var str_arr = v.coords.split(',');               
//                        newRegion = new Region();
//                        newRegion.addNation( v.nation );
//                        newRegion.deselect();
//                        newRegion.name = k;
//                        newRegion.text = v.name;
//                        addHandlers( newRegion );        
//                        newRegion.drawPolygon(str_arr);
//                        regions.addChild(newRegion);
//                    });
//                    stage.addChild(regions);
//                }
//            );
            
//            units = new createjs.Container();
//            circle = new createjs.Bitmap("img/Unit.png");
//            var pt, start;
//            
//            circle.on("mousedown", function(evt) {
//                $('body').bind('touchmove', function(e){e.preventDefault()});
//                pt = circle.globalToLocal(stage.mouseX, stage.mouseY);
//                start = {
//                    x: evt.target.x,
//                    y: evt.target.y
//                }
//            });
//            circle.on("pressmove", function(evt) {              
//                evt.target.x = evt.stageX - pt.x;
//                evt.target.y = evt.stageY - pt.y;
//                stage.update();
//            });
//            circle.on("pressup", function(evt) {
//                $('body').unbind('touchmove');
//                var revert = 1;
//                for( var i = 0; i < regions.getNumChildren(); i++ ) {
//                    var region = regions.getChildAt(i);
//                  //  region.alpha = 0;
//                    var st = circle.localToLocal(50,50,region);
//                    if ( region.hitTest( st.x,st.y ) && region.city ) { 
//                        region.alpha = 0.5;
//                        circle.alpha = 1;
//                        revert = 0;
//                    }
//                }
//                if ( revert ) {
//                    evt.target.x = start.x;
//                    evt.target.y = start.y;
//                }
//                stage.update();
//            });
//            units.addChild(circle);
//            stage.addChild(units);
//            stage.update();
            //createjs.Ticker.on("tick", tick);
//        }
