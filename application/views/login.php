<!DOCTYPE html>
<html lang="en" ng-app="TodoApp">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>

    <!-- AngularJS -->
    <script src="<?= base_url('assets/lib/angular/angular.min.js') ?>"></script>

    <!-- Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body ng-controller="LoginCtrl" class="p-6">

    <div class="max-w-md mx-auto bg-white shadow-md rounded p-6">
        <h1 class="text-2xl font-bold mb-4">Login</h1>

        <form ng-submit="submitForm()">


            <div class="mb-4">
                <label class="block mb-1">Email</label>
                <input type="email" ng-model="form.email"
                    class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label class="block mb-1">Password</label>
                <input type="password" ng-model="form.password"
                    class="w-full border rounded p-2" required>
            </div>

            <button type="submit"
                class="w-full bg-yellow-500 text-white p-2 rounded">
                Login
            </button>
        </form>

        <p class="mt-4 text-green-600" ng-if="message">{{ message }}</p>
    </div>

    <script>
        var app = angular.module("TodoApp", []);

        app.controller("LoginCtrl", function($scope, $http, $window) {
            $scope.form = {};
            $scope.message = "";

            $scope.submitForm = function() {
                $scope.message = ""
                $http.post("<?= base_url('/api/auth/login') ?>", $scope.form)
                    .then(function(response) {
                        $scope.message = response.data.message;
                        $window.location.href = "<?= base_url('/') ?>";
                    }, function(error) {
                        $scope.message = error.data.message;
                    });
            };
        });
    </script>
</body>

</html>