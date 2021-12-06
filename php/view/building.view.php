<?php

final class BuildingView {

	private $loc;
	private $input;
	private $modules;
	private $errors;
	private $messages;

	public function __construct($loc = array(), $input = array(), $modules = array(), $errors = array(), $messages = array()) {

		$this->loc = $loc;
		$this->input = $input;
		$this->modules = $modules;
		$this->errors = $errors;
		$this->messages = $messages;

	}

	public function adminBuildingList(BuildingListParameters $arg) {

		$body = '

			<div class="row mb-3">
				<div class="col-12 col-md-8 col-lg-6">
					' . PaginationView::paginate($arg->numberOfPages,$arg->currentPage,'/' . Lang::prefix() . 'building/buildings/') . '
				</div>
				<div class="col-12 col-md-4 col-lg-2 offset-lg-4">
					<a href="/' . Lang::prefix() . 'building/buildings/create/" class="btn btn-block btn-outline-success btn-sm"><span class="fas fa-plus"></span> ' . Lang::getLang('create') . '</a>
				</div>
			</div>

			<div class="table-container mb-3">

				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover table-sm">
						<thead class="thead-light">
							<tr>
								<th scope="col" class="text-center">' . Lang::getLang('buildingName') . '</th>
								<th scope="col" class="text-center">' . Lang::getLang('buildingPublished') . '</th>
								<th scope="col" class="text-center">' . Lang::getLang('action') . '</th>
							</tr>
						</thead>
						<tbody>' . $this->adminBuildingListRows($arg) . '</tbody>
					</table>
				</div>
			</div>
			
			<div class="row">
				<div class="col-12 col-md-8 col-lg-6">
					' . PaginationView::paginate($arg->numberOfPages,$arg->currentPage,'/' . Lang::prefix() . 'building/buildings/') . '
				</div>
			</div>
			

		';

		$card = new CardView('building_admin_list',array('container'),'',array('col-12'),Lang::getLang('adminBuildingList'),$body);
		return $card->card();

	}

	public function adminBuildingForm($type, $buildingID = null) {

		$building = new Building($buildingID);
		if (!empty($this->input)) {
			foreach($this->input AS $key => $value) { if(isset($building->$key)) { $building->$key = $value; } }
		}

		$form = $this->adminBuildingFormTabs($type, $buildingID) . '

			<form id="buildingForm' . ucfirst($type) . '" method="post" action="/' . Lang::prefix() . 'building/buildings/' . $type . '/' . ($buildingID?$buildingID.'/':'') . '">
				
				' . ($buildingID?'<input type="hidden" name="buildingID" value="' . $buildingID . '">':'') . '

				<div class="row">
				
					<div class="col-12">
					
						<div class="form-row">
						
							<div class="form-group col-12 col-md-8">
								<label for="buildingName">' . Lang::getLang('buildingName') . '</label>
								<input type="text" class="form-control" name="buildingName" value="' . $building->buildingName . '">
							</div>
							
							<div class="form-group col-12 col-md-8">
								<label for="buildingURL">' . Lang::getLang('buildingURL') . '</label>
								<input type="text" class="form-control" name="buildingURL" value="' . $building->buildingURL . '">
							</div>

							<div class="form-group col-12 col-md-4">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="buildingPublished" value="1"' . ($building->buildingPublished?' checked':'') . '>
									<label class="custom-control-label" for="buildingPublished">' . Lang::getLang('buildingPublished') . '</label>
								</div>
							</div>
							
						</div>

						<div class="form-row">
						
							<div class="form-group col-12">
								<label for="buildingDescription">' . Lang::getLang('buildingDescription') . '</label>
								<textarea id="building_admin_form_description" class="form-control" name="buildingDescription">' . $building->buildingDescription . '</textarea>
							</div>
							
						</div>
						
					</div>
				
				</div>
				
				<hr />

				<div class="form-row">
				
					<div class="form-group col-12 col-sm-4 col-md-3">
						<a href="/' . Lang::prefix() . 'building/admin/buildings/" class="btn btn-block btn-outline-secondary" role="button">' . Lang::getLang('returnToList') . '</a>
					</div>
					
					<div class="form-group col-12 col-sm-4 col-md-3 offset-md-3">
						<button type="submit" name="building-' . $type . '" class="btn btn-block btn-outline-'. ($type=='create'?'success':'primary') . '">' . Lang::getLang($type) . '</button>
					</div>
					
					<div class="form-group col-12 col-sm-4 col-md-3">
						<a href="/' . Lang::prefix() . 'building/admin/buildings/" class="btn btn-block btn-outline-secondary" role="button">' . Lang::getLang('cancel') . '</a>
					</div>
					
				</div>

			</form>

		';

		$header = Lang::getLang('building'.ucfirst($type)).($type=='update'?' ['.$building->buildingName.']':'');
		$card = new CardView('building_confirm_'.ucfirst($type),array('container'),'',array('col-12'),$header,$form);
		return $card->card();

	}

	public function adminBuildingConfirmDelete($buildingID) {

		$building = new Building($buildingID);

		$form = '

			<form id="building_form_delete" method="post" action="/' . Lang::prefix() . 'building/admin/buildings/">
				
				<input type="hidden" name="buildingID" value="' . $buildingID . '">

				<div class="form-row">
				
					<div class="form-group col-12 col-md-5">
						<label for="buildingName">' . Lang::getLang('buildingName') . '</label>
						<input type="text" class="form-control" value="' . $building->buildingName . '" disabled>
					</div>
					
					<div class="form-group col-12 col-md-4">
						<label for="buildingURL">' . Lang::getLang('buildingURL') . '</label>
						<input type="text" class="form-control" value="' . $building->buildingURL . '" disabled>
					</div>
					
					<div class="form-group col-12 col-md-3">
						<div class="custom-control custom-checkbox">
							<input type="checkbox" class="custom-control-input" id="buildingPublished" value="1" disabled' . ($building->buildPublished?' checked':'') . '>
							<label class="custom-control-label" for="buildingPublished">' . Lang::getLang('buildingPublished') . '</label>
						</div>
					</div>

				</div>

				<div class="form-row">

					<div class="form-group col-12">
						<label for="buildingDescription">' . Lang::getLang('buildingDescription') . '</label>
						<textarea id="building_admin_form_description" class="form-control" name="buildingDescription" disabled>' . $building->buildingDescription . '</textarea>
					</div>

				</div>

				<div class="form-row">
				
					<div class="form-group col-6 col-md-3 offset-md-6">
						<button type="submit" name="building-confirm-delete" class="btn btn-block btn-outline-danger">' . Lang::getLang('delete') . '</button>
					</div>
					
					<div class="form-group col-6 col-md-3">
						<a href="/' . Lang::prefix() . 'building/admin/buildings/" class="btn btn-block btn-outline-secondary" role="button">' . Lang::getLang('cancel') . '</a>
					</div>
					
				</div>
				
			</form>
		';

		$header = Lang::getLang('buildingConfirmDelete').' ['. $building->buildingName .']';
		$card = new CardView('building_confirm_delete',array('container'),'',array('col-12'),$header,$form);
		return $card->card();

	}

	public function buildingList(BuildingListParameters $arg) {

		$hpl = new BuildingList($arg);
		$buildings = $hpl->buildings();

		$buildingList = '<div class="container mt-3">';

		$buildingList .= '<h3 class="building-h">' .  Lang::getLang('buildings') . '</h3>';

		foreach ($buildings AS $buildingID) {

			$img = '';
			$imageFetch = new ImageFetch('Building', $buildingID, null, true);
			if ($imageFetch->imageExists()) {
				$img = '<span class="building-list-item-image-span">';
					$img .= '<img src="' . $imageFetch->getImageSrc() . '" class="building-list-item-image">';
				$img .= '</span>';
			}

			$building = new Building($buildingID);
			$buildingList .= '
				<div class="building-list-item row clickable" data-url="/' . Lang::prefix() . 'buildings/' . $building->buildingURL . '/">
					<div class="building-list-item-image col-12 col-md-3 col-lg-2">' . $img . '</div>
					<div class="building-list-item-building col-12 col-md-9 col-lg-10">
						<span class="building-list-item-building-name">' . $building->buildingName . '</span>
						<br />
						<span class="building-list-item-building-description">' . mb_substr($building->buildingDescription, 0, 100) . '...</span>
					</div>
				</div>
			';
		}

		$buildingList .= '</div>';

		return $buildingList;

	}

	public function buildingView($buildingID) {

		$building = new Building($buildingID);

		$view = '
		
			<div class="building-view container-fluid">

				<div class="row">
					
					<div class="col-12 col-sm-6 mb-3">' . $this->buildingCarousel($buildingID) . '</div>
					
					<div class="col-12 col-sm-6 mb-3">
						<h1 class="building-h">' . $building->buildingName . '</h1>
						<p>' . nl2br(htmlentities($building->buildingDescription),true) . '</p>
					</div>

				</div>
				
			</div>

		';

		return $view;

	}

	private function adminBuildingListRows(BuildingListParameters $arg) {

		$buildingList = new BuildingList($arg);
		$buildings = $buildingList->buildings();

		$rows = '';

		foreach ($buildings AS $buildingID) {

			$building = new Building($buildingID);

			$rows .= '
				<tr id="building_id_' . $buildingID . '" class="building-list-row" data-building-id="' . $buildingID . '">
					<th scope="row" class="text-left">' . $building->buildingName . '</th>
					<td class="text-center">' . ($building->buildingPublished?'&#10004;':'') . '</td>
					<td class="text-center text-nowrap">
						<a href="/' . Lang::prefix() . 'building/admin/buildings/update/' . $buildingID . '/" class="btn btn-sm btn-outline-primary">' . Lang::getLang('update') . '</a>
						<a href="/' . Lang::prefix() . 'building/admin/buildings/confirm-delete/' . $buildingID . '/" class="btn btn-sm btn-outline-danger">' . Lang::getLang('delete') . '</a>
					</td>
				</tr>
			';

		}

		return $rows;

	}

	public function adminBuildingFormTabs($type = 'create', $buildingID = null, $activeTab = 'building-form') {

		$buildingFormURL = '#';
		$updateOnly = true;

		if ($type == 'update' && ctype_digit($buildingID)) {
			$buildingFormURL = '/' . Lang::prefix() . 'building/admin/buildings/update/' . $buildingID . '/';
			$updateOnly = false;
		}

		$t = '

			<ul id="building_admin_form_nav_tabs" class="nav nav-tabs">
				<li class="nav-item">
					<a class="nav-link' . ($activeTab=='building-form'?' active':'') . '" href="' . $buildingFormURL . '">' . Lang::getLang('building') . '</a>
				</li>
				<li class="nav-item">
					<a class="nav-link' . ($updateOnly?' disabled':'') . ($activeTab=='images'?' active':'') . '" href="' . $buildingFormURL . 'images/"' . ($updateOnly?' tabindex="-1"':'') . '>' . Lang::getLang('buildingImages') . '</a>
				</li>
				<li class="nav-item">
					<a class="nav-link' . ($updateOnly?' disabled':'') . ($activeTab=='files'?' active':'') . '" href="' . $buildingFormURL . 'files/"' . ($updateOnly?' tabindex="-1"':'') . '>' . Lang::getLang('buildingFiles') . '</a>
				</li>
			</ul>
			
		';

		return $t;

	}

	private function buildingCarousel($buildingID) {

		$arg = new NewImageListParameters();
		$arg->imageObject = 'Building';
		$arg->imageObjectID = $buildingID;
		$nil = new NewImageList($arg);
		$images = $nil->images();

		$panels = '';
		for ($i = 0; $i < count($images); $i++) {
			$panels .= '
				<div class="carousel-item' . ($i==0?' active':'') . '">
					<img src="/image/' . $images[$i] . '" class="d-block w-100"">
				</div>
			';
		}

		$carousel = '
			<div id="building_carousel" class="carousel slide" data-ride="carousel">
				<div class="carousel-inner">' . $panels . '</div>
				<a class="carousel-control-prev" href="#building_carousel" role="button" data-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				</a>
				<a class="carousel-control-next" href="#building_carousel" role="button" data-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
				</a>
			</div>
		';

		return $carousel;

	}

}

?>