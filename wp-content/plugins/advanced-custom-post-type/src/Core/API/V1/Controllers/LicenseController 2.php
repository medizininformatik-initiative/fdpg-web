<?php

namespace ACPT\Core\API\V1\Controllers;

use ACPT\Admin\ACPT_Key_Value_Storage;
use ACPT\Admin\ACPT_License_Manager;

class LicenseController extends AbstractController
{
	public function deactivate(\WP_REST_Request $request)
	{
		$id = @$request['id'];

		if(!isset($id)){
			return [
				'success' => false
			];
		}

		$license = ACPT_License_Manager::getLicense();

		if($license['activation_id'] != $id){
			return [
				'success' => false
			];
		}

		$delete = ACPT_Key_Value_Storage::delete(ACPT_License_Manager::PRIVATE_KEY_NAME);

		return [
			'success' => $delete
		];
	}
}