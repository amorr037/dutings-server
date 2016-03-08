/**
 * Created by javier on 3/7/16.
 */
(function(){
    angular.module('dutings').controller("DrawController", [function(){
        console.log("In DrawController");
        var self = {};
        self.orientation = "portrait";
        self.cells = [];
        self.selectedCell = null;
        self.cells.push(new Cell(100, 100, 50, 50));

        self.orientationChanged = function(){
            console.log("Orientation Changed:");
            var orientationStyle = $('#orientation-style');
            console.log(orientationStyle);
            orientationStyle.remove();
            var header = $("head");
            orientationStyle = $('<style id="orientation-style" type="text/css" media="print">@page { size: '+self.orientation+'; }</style>');
            header.append(orientationStyle);
        };

        self.cellChanged = function(cell){
            console.log("Cell changed:");
            console.log(cell);
        };

        function Cell(width, height, top, left, type){
            if(!type){
                type = "text-field";
            }
            var inputs = {
                "text": {"value": ""},
                "number": {"value": ""},
                "boolean": {"value": false}
            };
            this.width = width;
            this.height = height;
            this.top = top;
            this.left = left;
            this.type = type;
            this.input = inputs[type];
        }

        return self;
    }]);
})();