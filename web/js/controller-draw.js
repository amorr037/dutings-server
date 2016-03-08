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
        self.menuTab = "create";

        self.inputTypes = {
            "text": {"value": "text"},
            "number": {"value": 0},
            "checkbox": {"value": false},
            "label": {"value": "label"}
        };

        self.editCell = function(cell){
            self.selectedCell = cell;
            self.menuTab = "edit";
        };

        self.createCell = function(type){
            var cell = new Cell(50, 50, 0, 0, type);
            self.cells.push(cell);
            self.editCell(cell);
        };

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

            this.width = width;
            this.height = height;
            this.top = top;
            this.left = left;
            this.type = type;
            this.input = JSON.parse(JSON.stringify(self.inputTypes[type]));
        }

        return self;
    }]);
})();