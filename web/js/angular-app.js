/**
 * Created by javier on 3/5/16.
 */
(function(){
    angular.module('dutings', [
        'ui.router'
    ]);

    angular.module('dutings').config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {
        $urlRouterProvider.otherwise('/draw');

        $stateProvider.state('app', {
            url: '/draw',
            templateUrl: "/web/pages/draw.html"
        });
    }]);

    angular.module('dutings').controller("DrawController", [function(){
        console.log("In DrawController");
        var self = {};
        self.orientation = "portrait";
        self.columns = 5;
        self.rows = 12;
        self.cellsRows = [];
        self.selectedCell = null;
        self.setCellRows = function(){
            var r, c, row;
            while(self.cellsRows.length < self.rows){
                row = [];
                self.cellsRows.push(row);
                for(c = 0 ; c < self.columns; c++){
                    row.push(new Cell(1, 1, self.cellsRows.length-1, c));
                }
            }

            if(self.cellsRows.length > self.rows){
                self.cellsRows.splice(self.rows, self.cellsRows.length - self.rows);
            }

            while(self.cellsRows[0].length < self.columns){
                for(r = 0 ; r < self.cellsRows.length; r++){
                    row = self.cellsRows[r];
                    for(c = row.length ; c < self.columns; c++){
                        row.push(new Cell(1, 1, r, c));
                    }
                }
            }
            if(self.cellsRows[0].length > self.columns){
                for(r = 0 ; r < self.cellsRows.length; r++){
                    row = self.cellsRows[r];
                    row.splice(self.columns, row.length - self.columns);
                }
            }
            console.log("Rows: "+self.cellsRows.length + " Columns: "+self.cellsRows[0].length);
            console.log(self.cellsRows);
        };

        self.setCellRows();

        self.cellColumnsChanged = function(cell){
            console.log("Changed Col For:");
            console.log(cell);
            if(cell.columns <= 0){
                cell.columns = 1;
            }else if(cell.col + cell.columns > self.columns){
                cell.columns = self.columns - cell.col;
            }
            var i, j, rowIndex, row;
            if(cell.prevCols > cell.columns){
                var deleted = cell.prevCols - cell.columns;
                for(i = 0 ; i < cell.rows; i++) {
                    rowIndex = cell.row + i;
                    row = self.cellsRows[rowIndex];
                    for(j = 0 ; j < deleted; j++){
                        row.splice(cell.col + 1,0,new Cell(1,1,rowIndex,cell.col + 1));
                    }
                    for(j = cell.col; j < row.length; j++){
                        row[j].col = j;
                    }
                }
            }else if(cell.prevCols < cell.columns){
                var added =  cell.columns - cell.prevCols;
                for(i = 0 ; i < cell.rows; i++) {
                    rowIndex = cell.row + i;
                    row = self.cellsRows[rowIndex];
                    row.splice(cell.col + 1, added);
                    for(j = cell.col; j < row.length; j++){
                        row[j].col = j;
                    }
                }
            }
            cell.prevCols = cell.columns;
        };

        self.cellRowsChanged = function(cell){
            console.log("Changed Row For:");
            console.log(cell);
            if(cell.rows <= 0){
                cell.rows = 1;
            }else if(cell.row + cell.rows > self.rows){
                cell.rows = self.rows - cell.row;
            }
            var i, j, rowIndex, row, modified;
            if(cell.prevRows > cell.rows){
                modified = cell.prevRows - cell.rows;
                for(i = 0 ; i < modified; i++){
                    rowIndex = cell.row + cell.rows + i;
                    row = self.cellsRows[rowIndex];
                    for(j = 0; j < cell.columns; j++){
                        row.splice(cell.col+j, 0, new Cell(1,1,rowIndex,cell.col+j));
                    }
                    for(j = cell.col; j < row.length; j++){
                        row[j].col = j;
                    }
                }
            }else if(cell.prevRows < cell.rows){
                modified = cell.rows - cell.prevRows;
                for(i = 0 ; i < modified; i++){
                    rowIndex = cell.row + cell.rows + i - 1;
                    row = self.cellsRows[rowIndex];
                    for(j = 0; j < cell.columns; j++){
                        row.splice(cell.col, 1);
                    }
                    for(j = cell.col; j < row.length; j++){
                        row[j].col = j;
                    }
                }
            }
            cell.prevRows = cell.rows;
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

        function Cell(rows, cols, row, col){
            this.rows = rows;
            this.columns = cols;
            this.row = row;
            this.col = col;

            this.prevRows = this.rows;
            this.prevCols = this.columns;

            this.width = function(){
                var percent = this.columns / self.columns * 100;
                return percent+"%";
            };
            this.height = function(){
                var percent = this.rows / self.rows * 100;
                return percent+"%";
            };
        }

        return self;
    }]);
})();