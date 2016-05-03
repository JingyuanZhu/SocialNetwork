(function () {
    'use strict';
    
    angular
        .module('app')
        .controller('HomeController', ['$scope', '$routeParams', '$http', '$sce',
            function($scope, $routeParams, $http, $sce) {
                                       
                $scope.username = $routeParams.username;

                /*
                $http.get('profile/' + $routeParams.username).success(function(data) {
                    $scope.profile = data;
                    */
                console.log('prepare to http get');
                //$http.get('http://bizzbuz-linxin-li.herokuapp.com/api/user-all').then(function(response) {
                $http.get("http://localhost:8888/api/user").then(function(response) {
                    $scope.test = response.data;
                });
                console.log($scope.username);
                
                $http.get('http://localhost:8888/api/user/' + $routeParams.username).then(function(response) {
                    console.log(response.data);
                    $scope.profile = response.data[0];
                    //$scope.profile = JSON.parse(response.data);
                });
                
                /* for test
                $scope.profile = {
                    username: 'Jeff0003',
                    age: 27,
                    zipCode: "11201",
                    duration: 4
                };*/
                
                /*
                $http.get('friends/' + $routeParams.username).success(function(data) {
                    $scope.friends = data;
                    */
                $scope.friends = [
                    {
                        username: 'Jeff0003',
                    },
                    {
                        username: 'Jeff0004'
                    }
                ];
                
                /*
                $http.get('posts/' + $routeParams.username).success(function(data) {
                    $scope.posts = data;
                    */
                $scope.posts = [
                    {
                        postId: '001',
                        username: 'Jeff0002',
                        body: 'Hello, Jeff0001',
                        video: "https://www.youtube.com/embed/MtCMtC50gwY",
                        img: ""
                    },
                    {
                        postId: '002',
                        username: 'Jeff0003',
                        body: 'How are you, Jeff0002',
                        video: "",
                        img: "https://media-cdn.tripadvisor.com/media/photo-s/03/9b/2d/f2/new-york-city.jpg"
                    },
                    {
                        postId: '003',
                        username: 'Jeff0004',
                        body: 'I\'m fine, Thanks!',
                        video: "https://www.youtube.com/embed/Nsec4hWZz2M",
                        img: ""
                    }
                ];
                $scope.trustSrc = function(src) {
                    return $sce.trustAsResourceUrl(src);
                };

                $scope.addPost = function() {
                    //http post
                    if ($scope.postBody) {
                        $scope.posts.unshift( {
                            username: $scope.username,
                            body:$scope.postBody
                        })
                    $scope.postBody = null;
                    }
                };
                
                // http get comments/:postId
                $scope.comments = [
                    {
                        postId: '001',
                        author: "jeff001",
                        recipient: "jeff002",
                        body: "Hello, I like your post"
                    }
                ];
                
                //$scope.addComment = function(author, postId) {
                $scope.addComment = function() {
                    console.log($scope.vm.commentBody);
                    if ($scope.vm.commentBody) {
                        //http post postId, $scope.commentBody, @scope.username, 
                        console.log('success');
                        $scope.comments.unshift( {
                            postId: "",
                            author: $scope.username,
                            recipient: "unknown",
                            body: $scope.vm.commentBody
                        })
                    $scope.commentBody = null;
                    }
                    console.log($scope.comments);
                };
                

                
                /*
                http.get('peopleMayKnow/' + $routeParams.username).success(function(data) {
                    $scope.peopleMayKnow = data;
                    */
                $scope.peopleMayKnow = [
                    {
                        username: 'Jeff0004'
                    },
                    {
                        username: 'Jeff0005'
                    }
                ];
                }]);
                
    angular
        .module('app')
        .directive('postdirective', function() {
        return {
            restrict: 'E',
            template: "templates/post-template.html",
            link: function(scope, el, attr) {
                //if(scope.post.video == "") {
                    
            
                }
            
            }
        });
})();  