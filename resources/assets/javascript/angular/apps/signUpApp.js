(function() {
    var signUpApp = angular.module('app', []);

    signUpApp.controller("signUpController", ["$scope", "signUpService", "flashMessage", function($scope, signUpService, flashMessage) {
        $scope.hasSignedUp = false;
        $scope.isSigningUp = false;
        $scope.signUpButtonText = "Sign Up";

        $scope.signUp = function() {
            $scope.isSigningUp = true;
            $scope.signUpButtonText = "Signing Up...";

            signUpService.go($scope.user).then(function(response) {
                $scope.hasSignedUp = true;
                $scope.isSigningUp = false;
            }, function() {
                // Otherwise show error
                $scope.isSigningUp = false;
            });
        }
    }]);

    signUpApp.service("signUpService", ["$http", function($http) {
        this.go = function(credentials) {
            return $http.post('/auth/signup', credentials);
        };
    }]);
})();