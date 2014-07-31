@extends('layouts.default')

@section('content')

<script>
	
	//Get the json config files and make them more manageable to reference
	var config =       <% file_get_contents('ds/lib/ds/tips-config.json') %>;
	var routing =       <% file_get_contents('ds/lib/ds/routing-config.json') %>;

	var campaignTips = config.tips;
	var startCampaignTransitions = routing.startCampaignTransitions;
	var yesNoPaths = routing.yesNoPaths;
	
	var origin = JSON.parse(JSON.stringify(config));



</script>

<main>




<div ng-controller="MainCtrl" id="moduleHolder">
	
	<div class="center">
		<!-- <button ng-show="edited" onclick="saveFile(config)" class="btn">Save</button> -->
		<button class="btn" ng-click="setPositions()">Reposition</button>
		<button ng-show="edited" ng-click="save(config)" class="btn">Save</button>
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
			<span class="button" click-to-edit="key" index="{{$index}}"></span>
		</div><span class="mega-octicon octicon-plus med-icon button addButton fade" add-new-item="tip"></span>
		<br />
		<div class="autoMargin">
			<!-- <button onclick="saveFile(config)" class="hide">Update Database</button> -->
			<!-- <span update-db="update"></span> -->
			
		</div>
		</td>
	</tr>
</table>

<div style="clear: both;">
	<button add-new-module=""></button>
</div>

<div style="clear: both;">
	<button onclick="saveFile(config)">
		Save
	</button>
</div>

<div id="results">
	
</div>

</div>

<table ng-controller="MainCtrl">
	<tr >
		<th ng-repeat="tip in yesNoPaths"> {{tip.__comments}} </th>
	</tr>
	<tr >
		<td ng-repeat="tip in yesNoPaths">
		<div ng-repeat="(key, code) in tip" ng-hide="$first">
			<strong>{{ key }}</strong>:
			<input type="text" ng-model="code" class="write" />
			<span class="button" click-to-edit="code" key="key" index="{{key}}"></span>
		</div><span class="mega-octicon octicon-plus med-icon button addButton" add-new-item="tip"></span>
		
		</td>
	</tr>
</table>
<div>
	<button onclick="saveFile(routing)">
		Save
	</button>
</div>


<table ng-controller="MainCtrl">
	<tr >
		<th ng-repeat="tip in startCampaignTransitions"> {{tip.__comments}} </th>
	</tr>
	<tr >
		<td ng-repeat="tip in startCampaignTransitions">
		<div ng-repeat="(key, code) in tip" ng-hide="$first">
			<strong>{{ key }}</strong>:
			<input type="text" ng-model="code" class="write" />
			<span class="button" click-to-edit="code" key="key" index="{{key}}"></span>
		</div><span class="mega-octicon octicon-plus med-icon button addButton" add-new-item="tip"></span></td>
	</tr>
</table>

<div>
	<button onclick="saveFile(routing)">
		Save
	</button>
</div>


</main>

@stop
