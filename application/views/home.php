<!DOCTYPE html>
<html lang="en" ng-app="TodoApp">

<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Task Manager</title>
	<script src="<?= base_url('assets/lib/angular/angular.min.js') ?>"></script>
	<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body ng-controller="TodoAppController">
	<div class="mx-auto max-w-2xl p-3">
		<h1 class="text-center font-bold text-4xl py-3">Task Manager</h1>

		<!-- Form tạo task -->
		<div class="flex flex-col gap-2 bg-green-100 p-3 mb-3 rounded">
			<div class="flex flex-col">
				<label for="title">Title</label>
				<input type="text" id="title" class="outline px-3 p-2 border rounded"
					placeholder="Task title" ng-model="newTask.title" />
			</div>

			<div class="flex flex-col">
				<label for="status">Status</label>
				<select id="status" class="p-2 border rounded" ng-model="newTask.status">
					<option value="pending">Pending</option>
					<option value="in_progress">In Progress</option>
					<option value="completed">Completed</option>
				</select>
			</div>

			<div class="flex flex-col">
				<label for="due_date">Due date</label>
				<input id="due_date" type="date" class="p-2 border rounded"
					ng-model="newTask.due_date" />
			</div>

			<button ng-click="addTask()" type="button"
				class="bg-blue-700 p-2 text-white cursor-pointer rounded">
				Add Task
			</button>
		</div>

		<!-- Danh sách task -->
		<h2 class="text-2xl font-bold mb-2">Task list</h2>
		<ul class="bg-yellow-100 p-3 pb-1 rounded">
			<li ng-repeat="task in tasks track by task.id"
				class="flex flex-col md:flex-row md:items-center justify-between mb-3 bg-blue-100 p-3 rounded shadow">

				<div ng-if="editingTaskId !== task.id">
					<p class="font-semibold">{{ task.title }}</p>
					<p class="text-sm text-gray-700">{{ task.description }}</p>
					<p class="text-sm">Status: <span class="font-bold">{{ task.status }}</span></p>
					<p class="text-sm">Due: {{ task.due_date | date }}</p>
				</div>

				<!-- Form edit -->
				<div ng-if="editingTaskId === task.id" class="flex flex-col gap-2">
					<input type="text" ng-model="editingTask.title" class="p-2 border rounded" />
					<select ng-model="editingTask.status" class="p-2 border rounded">
						<option value="pending">Pending</option>
						<option value="in_progress">In Progress</option>
						<option value="completed">Completed</option>
					</select>
					<input type="date" ng-model="editingTask.due_date" class="p-2 border rounded" />
				</div>

				<div class="flex gap-2 mt-2 md:mt-0">
					<button ng-if="editingTaskId !== task.id"
						ng-click="startEdit(task)"
						class="bg-yellow-500 text-white px-3 py-1 rounded">Edit</button>

					<button ng-if="editingTaskId === task.id"
						ng-click="saveTask(task.id)"
						class="bg-green-600 text-white px-3 py-1 rounded">Save</button>

					<button ng-if="editingTaskId === task.id"
						ng-click="cancelEdit()"
						class="bg-gray-500 text-white px-3 py-1 rounded">Cancel</button>

					<button ng-click="deleteTask(task.id)" class="bg-red-600 text-white px-3 py-1 rounded">Delete</button>
				</div>
	</div>
	</li>

	</ul>
	</div>

	<script>
		var TodoApp = angular.module("TodoApp", [])
			.controller("TodoAppController", ["$scope", "$http", function($scope, $http) {

				$scope.tasks = [];
				$scope.newTask = {
					status: "pending"
				};

				// Load tasks từ API
				$scope.loadTasks = function() {
					$http.get("<?= base_url('/api/tasks') ?>").then(function(res) {

						$scope.tasks = res.data.data.map(function(task) {
							if (task.due_date) {
								task.due_date = new Date(task.due_date); // convert string → Date
							}
							return task;
						});;

					});
				};
				$scope.loadTasks();

				//  Add task
				$scope.addTask = function() {
					if ($scope.newTask.title) {
						$http.post("<?= base_url('/api/tasks') ?>", $scope.newTask).then(function(res) {
							$scope.newTask = {
								status: "pending"
							};
						});
					}
					$scope.loadTasks();
				};

				//   Update task
				$scope.editingTaskId = null;
				$scope.editingTask = null;

				// Bắt đầu edit
				$scope.startEdit = function($task) {
					$scope.editingTaskId = $task.id;
					$scope.editingTask = {
						title: $task['title'],
						status: $task['status'],
						due_date: new Date($task['due_date']),
					};
				};

				// Lưu task đã edit
				$scope.saveTask = function(id) {
					console.log(id)
					let payload = angular.copy($scope.editingTask);
					if (payload.due_date) {
						payload.due_date = new Date(payload.due_date).toISOString().split("T")[0]; // YYYY-MM-DD
					}

					$http.put("<?= base_url('/api/tasks/') ?>" + id, payload).then(function(res) {
						$scope.editingTaskId = null;
						$scope.editingTask = null;
						$scope.loadTasks();
					});
				};

				// Hủy edit
				$scope.cancelEdit = function() {
					$scope.editingTaskId = null;
					$scope.editingTask = null;
				};



				//   Delete task
				$scope.deleteTask = function(id) {
					$http.delete("<?= base_url('/api/tasks/') ?>" + id).then(function() {
						console.log($rres.data);
					});
					$scope.loadTasks();
				};
			}]);
	</script>
</body>

</html>