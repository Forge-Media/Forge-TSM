var forge = angular.module('forgeApp', ['ui.tree', 'ngRoute', 'ui.bootstrap'])
    .config(['$routeProvider', '$compileProvider', function ($routeProvider, $compileProvider) {
        $routeProvider
            .when('/forge/web/index.html', {
                controller: 'TeamspeakCtrl',
                templateUrl: 'angular/views/channel-tree.html'
            })
            .otherwise({
                redirectTo: '/forge/web/index.html'
            });

        // testing issue #521
        $compileProvider.debugInfoEnabled(false);
    }]);

forge.controller('TeamspeakCtrl', ['$scope', function ($scope) {

    $scope.currentItem = {
        'id': '',
        'title': '',
        'topic': '',
        'nodes': []
    };

    $scope.FieldValid = true;

    $scope.fieldValidator = function () {

    };

    $scope.remove = function (scope) {
        scope.remove();
    };

    $scope.newSubItem = function (scope) {
        var nodeData = scope.$modelValue;
        nodeData.nodes.push({
            id: nodeData.id * 10 + nodeData.nodes.length,
            title: nodeData.title + '.' + (nodeData.nodes.length + 1),
            nodes: []
        });
    };

    $scope.visible = function (item) {
        return !($scope.query && $scope.query.length > 0
        && item.title.indexOf($scope.query) == -1);

    };

    $scope.getNodeData = function (index) {
        $scope.currentItem = index;
        console.log(index);
    };

    $scope.getNodeData($scope.data);
    $scope.data = [{
        'id': 1,
        'title': '[cspacer000] Clan Name',
        'topic': 'Channel Topic',
        'nodes': [
            {
                'id': 10,
                'title': 'Channel 1',
                'topic': 'Channel 1 Topic',
                'nodes': []
            },
            {
                'id': 11,
                'title': 'Channel 2',
                'topic': 'Channel 2 Topic',
                'nodes': []
            },
            {
                'id': 12,
                'title': 'Channel 3',
                'topic': 'Channel 3 Topic',
                'nodes': []
            }
        ]
    }];
}]);