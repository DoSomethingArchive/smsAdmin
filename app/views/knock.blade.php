@extends('layouts.default')

@section('content')

<script>
	var config = <% file_get_contents('ds/lib/ds/tips-config.json') %>;
	var routing = <% file_get_contents('ds/lib/ds/routing-config.json') %>;
	
	var campaignTips = config.tips;
	var startCampaignTransitions = routing.startCampaignTransitions;
	var yesNoPaths = routing.yesNoPaths;
	
	
	// function tipsController($scope) {
// 			
		// $scope.tips = campaignTips;
// 		
// 		
	// }
	
	app = angular.module("codeModel", []);
	
	

app.directive("clickToEdit", function() {
    var editorTemplate = '<span class="click-to-edit">' +
        '<span ng-hide="view.editorEnabled" ng-click="enableEditor()">' +
            '{{value}} ' +
            
        '</span>' +
        '<span ng-show="view.editorEnabled">' +
            '<input ng-model="view.editableValue">' +
            '<span ng-click="save()" class="mega-octicon octicon-check med-icon button"></span>' +
            '<span ng-click="disableEditor()" class="mega-octicon octicon-remove-close med-icon button cancel"></span>' +
        '</span>' +
    '</span>';

    return {
        restrict: "A",
        replace: true,
        template: editorTemplate,
        scope: {
            value: "=clickToEdit",
        },
        controller: function($scope) {
            $scope.view = {
                editableValue: $scope.value,
                editorEnabled: false
            };

            $scope.enableEditor = function() {
                $scope.view.editorEnabled = true;
                $scope.view.editableValue = $scope.value;
            };

            $scope.disableEditor = function() {
                $scope.view.editorEnabled = false;
            };

            $scope.save = function() {
                $scope.value = $scope.view.editableValue;
                $scope.disableEditor();
            };
        }
    };
});

app.controller("tipsControl", function($scope) {
    
    $scope.codes = campaignTips;
    
});

app.controller("transitionsControl", function($scope) {
	$scope.codes = startCampaignTransitions;
});

app.controller("yesNoControl", function($scope) {
	$scope.codes = yesNoPaths;
});

	
	angular.element(document).ready(function() {
    angular.bootstrap(document, ["codeModel"]);
});
</script>


	<table ng-controller="yesNoControl">
		<tr >
			<th ng-repeat="tip in codes">
				{{tip.__comments}}
			</th>
		</tr>
		<tr >
			<td ng-repeat="tip in codes">
				<div ng-repeat="(key, code) in tip" ng-hide="$first">
					<strong>{{ key }}</strong>: <input type="text" ng-model="code" class="write" />
					<span class="button" click-to-edit="code"></span>
				</div>
			</td>
		</tr>
	</table>
	
	<table ng-controller="transitionsControl">
		<tr >
			<th ng-repeat="tip in codes">
				{{tip.__comments}}
			</th>
		</tr>
		<tr >
			<td ng-repeat="tip in codes">
				<div ng-repeat="(key, code) in tip" ng-hide="$first">
					<strong>{{ key }}</strong>: <input type="text" ng-model="code" class="write" />
					<span class="button" click-to-edit="code"></span>
				</div>
			</td>
		</tr>
	</table>



	<table ng-controller="tipsControl">
		<tr >
			<th ng-repeat="tip in codes">
				{{tip.name}}
			</th>
		</tr>
		<tr >
			<td ng-repeat="tip in codes">
				<div ng-repeat="code in tip.optins">
					<input type="text" class="write" />
					<span class="button" click-to-edit="code"></span>
				</div>
			</td>
		</tr>
	</table>
<pre>
	<div ng-model="campaignTips">
		
	</div>
</pre>

@stop
