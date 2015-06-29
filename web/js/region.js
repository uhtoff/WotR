define(["createjs"], function(cjs){
    function Region(id,v) {
        createjs.Shape.call(this);
        this.units = [];
        this.name = id;
        this.text = v.name;
        this.town = v.town;
        this.stronghold = v.stronghold;
        this.city = v.city;
        this.unitPos = v.unitPos;        
    }
    Region.prototype = Object.create(createjs.Shape.prototype);
    Region.prototype.constructor = Region;
    Region.prototype.addNation = function( nation_id ) {
        colourArr = {
            '0':'black',
            '1':'saddlebrown',
            '2':'limegreen',
            '3':'lightskyblue',
            '4':'green',
            '5':'blue',
            '6':'red',
            '7':'orange',
            '8':'yellow' };
        this.setFillColour( colourArr[nation_id] );
    };
    Region.prototype.unselectable = function() {
        this.setFillColour('black');
        this.alpha = 0.8;
        this.unselectable = 1;
    };
    Region.prototype.select = function() {
        if ( this.unselectable !== 1 ) {
            this.selected = 1;
            this.alpha = 0.5;
        }
    };
    Region.prototype.selectable = function() {
        if ( this.unselectable !== 1 ) {
            this.selected = 0;
            this.alpha = 0.1;
        }
    };
    return Region;
});


