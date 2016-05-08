var forge = angular.module('forgeApp', ['ui.tree', 'ngRoute', 'ui.bootstrap'])
    .config(['$routeProvider', '$compileProvider', function ($routeProvider, $compileProvider) {
        $routeProvider
            .when('/forge/web/index.html', {
                controller: 'ChannelCtrl',
                templateUrl: 'angular/views/channel-tree.html'
            })
            .otherwise({
                redirectTo: '/forge/web/index.html'
            });

        // testing issue #521
        $compileProvider.debugInfoEnabled(false);
    }]);


forge.factory("ChannelFactory", function ($http) {
    var requestApi = {
        http: function (methode, location, data) {
            if (methode === "GET") {
                return $http({
                    method: methode,
                    url: location
                });
            }
            if (methode === "POST") {
                return $http({
                    method: methode,
                    url: location,
                    data: data
                });
            }
        }
    };

    return {
        createChannels: function (data) {
            // var res;
            $.post("core.php", {"data": data}, function (response) {
                //console.log(response);
                return response;
            });

            //return res;

        }
    };

});

forge.filter('to_trusted', ['$sce', function ($sce) {
    return function (text) {
        return $sce.trustAsHtml(text);
    };
}]);


forge.controller('ChannelCtrl', ['$scope', 'ChannelFactory', function ($scope, ChannelFactory) {
    //$scope.maxDepth = 2;
    //$scope.currentItem = {
    //    'id': '',
    //    'title': '',
    //    'topic': '',
    //    'nodes': []
    //};

    $scope.treeOptions = {};

    $scope.currentItem = null;

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

    $scope.deployChannels = function () {
        var processdata = $scope.data;

        var counter = 0;

        var passedData = [];
        for (var i = 0; i < processdata.length; i++) {
            passedData.push({
                'channel_name': '[cspacer000] ' + processdata[i].title,
                'channel_topic': processdata[i].topic
            });
            counter++;

            console.log(counter);
            for (var k = 0; k < processdata[i].nodes.length; k++) {
                passedData.push({
                    'channel_name': processdata[i].nodes[k].title,
                    'channel_topic': processdata[i].nodes[k].topic
                });
                counter++;
                console.log(counter);
            }
        }

        ChannelFactory.createChannels(passedData);
        //    .then(function (response) {
        //    $scope.output = response;
        //});
    };

    $scope.valiadateChannelNameLength = function (data) {
        if (data.title.length > 40 || data.title.length < 3) {
            data.namelengtherror = "Please ensure the channel name is <strong>between 3 and 40 characters</strong>";
        } else {
            data.namelengtherror = "";
        }
    };

    $scope.valiadateChannelTopicLength = function (data) {
        if (data.topic.length > 8192) {
            data.topiclengtherror = "Please ensure the channel topic has no more than <strong>8192 characters</strong>";
        } else {
            data.topiclengtherror = "";
        }
    };

    $scope.getNodeData = function (index) {
        $scope.currentItem = index;
    };

    $scope.getNodeData($scope.data);

    $scope.data = [{
        'id': 1,
        'title': 'Clan Name',
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