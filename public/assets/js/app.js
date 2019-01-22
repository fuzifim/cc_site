/**
 * Created by ADMIN on 16/7/2016.
 */
var webApp = angular.module('application',[],function($interpolateProvider){
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});
// Message
webApp.controller('MessageController',function($scope, $http, MessageManagement){

    $scope.messageUnread = 0;
    $scope.getMessageUnread = function(){
        MessageManagement.messageUnread($scope.lastpage)
            .success(function (data, status, headers, config) {
                $scope.messageUnread = data[0].message_unread;
            });
    };
});

webApp.factory('MessageManagement',function($http){
    return {
        messageUnread: function(status,page){
            return $http({
                method: 'GET',
                url : '/ajax/message/getMessagesUnread',
                dataType: 'json'
            });
        }
    }//end function
});