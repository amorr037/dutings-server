/**
 * Created by javier on 3/7/16.
 */
(function(){
    angular.module('dutings').directive('dutingsCell', function() {
        function getOnDragStopFunction(element, cell, onChange){
            return function(event, ui){
                cell.top = parseInt(element.css("top"));
                cell.left = parseInt(element.css("left"));
                if(onChange){
                    onChange()
                }
            }
        }
        function link(scope, element, attrs){
            console.log("Link: ");
            console.log(scope);
            console.log(element);
            console.log(attrs);
            var cell = scope.cell;

            element.draggable({
                grid: [ 10, 10 ],
                containment: "parent",
                stop: getOnDragStopFunction(element, cell, scope.onChange)
            });

            element.resizable({
                grid: [ 10, 10 ],
                containment: "parent"
            });

            element.css({
                "left": cell.left+"px",
                "top": cell.top+"px",
                "width": cell.width,
                "height": cell.height
            });
        }
        return {
            restrict: 'A',
            link: link,
            scope: {
                cell: "=dutingsCell",
                onChange: "&onChange"
            }
        };
    });
})();