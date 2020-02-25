<?php

class shopUniparamsPluginBackendUpdatelistController extends waJsonController {
	
	public function execute() {
		$list = waRequest::post();
		$model = new shopUniparamsListsModel();
		$glob = 0;

		$indexes = array_fill(0, 1000, 0);

        $list = $list['li'];
        $db = $model->select('id, front_index, parent_id')->order('front_index ASC')->fetchAll();
        $db_copy = $db;

        foreach ($list as $key => $val) {
            $old_elem = array_search($key, array_column($db, 'front_index'));
            $db_copy[$old_elem]['front_index'] = $glob++;
            if ($val == null || $val == 'null') {
                $db_copy[$old_elem]['parent_id'] = null;
            } else {
                $parent = array_search($val, array_column($db, 'front_index'));
//                $db_copy[$old_elem]['front_index'] = $indexes[$db[$parent]['id']]++;
                $db_copy[$old_elem]['parent_id'] = $db[$parent]['id'];
            }
        }

        foreach ($db_copy as $key => $val) {
			$model->updateById($val['id'], array('front_index' => $val['front_index'], 'parent_id' => $val['parent_id']));
		}

	}

}