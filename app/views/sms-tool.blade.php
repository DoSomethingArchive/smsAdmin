@extends('layouts.default')

@section('content')

<script>
	
	//Get the json config files and make them more manageable to reference
	var config =       <% file_get_contents('app/config/tips-config.json') %>;
	var routing =       <% file_get_contents('app/config/routing-config.json') %>;

	var campaignTips = config.tips;
	var startCampaignTransitions = routing.startCampaignTransitions;
	var yesNoPaths = routing.yesNoPaths;
	



</script>

<main>




<div ng-controller="MainCtrl" id="moduleHolder">
	
	<div class="sectionHeader">Tips</div>
	
	<div class="center">
		<button class="btn" ng-click="setPositions()">Reposition</button>
		<button ng-show="edited" ng-click="save('tips')" class="btn">Save</button>
	</div>
	
	

<table ng-repeat="(key, tip) in campaignTips" class="modules" draggable ng-style="{left: tip.left, top: tip.top}" xpos="tip.left" ypos="tip.top" >
	
	<tr >
		<th> <span class="button" edit-name="tip.__comments" key="key"></span> <span key="key" remove-module="removeModule">hi</span>
		</th>
	</tr>
	<tr >
		<td>
		<div ng-repeat="key in tip.optins track by $index" ng-init="code = tip.optins[key]">

			<input class="write" ng-model="key" />
			<span class="button" edit-code="key" index="{{$index}}"></span>
		</div><span class="mega-octicon octicon-plus med-icon button addButton fade" add-new-code="tip"></span>
		</td>
	</tr>
</table>

<div style="clear: both;">
	<button add-new-module=""></button>
</div>
</div>
<div ng-controller="MainCtrl">
<div>
	
	<div class="sectionHeader">Routing</div>

<div class="center">
		<button class="btn" ng-click="setPositions()">Reposition</button>
		<button ng-show="edited" ng-click="save('routing')" class="btn">Save</button>
	</div>

<table ng-repeat="tip in yesNoPaths" class="modules">
	<tr >
		<th > {{tip.__comments}} </th>
	</tr>
	<tr >
		<td >
		<div ng-repeat="(key, code) in tip" ng-if="key !== 'left' && key !== 'top' && key !== '__comments'">
			<strong>{{ key }}</strong>:
			<input type="text" ng-model="code" class="write" />
			<span class="button" edit-code="code" key="key" index="{{key}}"></span>
		</div><span class="mega-octicon octicon-plus med-icon button addButton" add-new-code="tip"></span>
		
		</td>
	</tr>
</table>



<table ng-repeat="tip in startCampaignTransitions" class="modules" >
	<tr >
		<th > {{tip.__comments}} </th>
	</tr>
	<tr >
		<td >
		<div ng-repeat="(key, code) in tip" ng-if="key !== 'left' && key !== 'top' && key !== '__comments'" >
			<strong>{{ key }}</strong>:
			<input type="text" ng-model="code" class="write" />
			<span class="button" edit-code="code" key="key" index="{{key}}"></span>
		</div><span class="mega-octicon octicon-plus med-icon button addButton" add-new-code="tip"></span></td>
	</tr>
</table>


</div>
</div>
</main>

@stop
