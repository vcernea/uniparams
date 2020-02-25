<?php

class shopUniparamsPluginBackendRemovegrouplistController extends waJsonController {

    public function execute() {
        $model = new shopUniparamsListsModel();
        $list_params = new shopUniparamsListsParamsModel();
        $data = waRequest::post();

        $list = $model->getById($data['list_id']);

        $lists_inside_group = $model->select("*")->where('`parent_id`="'.$list['id'].'"');
        foreach ($lists_inside_group as $el) {
            $max_index = $model->query('SELECT MAX(front_index) AS max_index FROM shop_uniparams_lists WHERE parent_id IS NULL')->fetchAll();
            $model->updateById($el['id'], array('parent_id' => null, 'front_index' => $max_index[0]['max_index']+1));
        }

        $items_old = $list_params->getByField(array('list_id' => $data['list_id']), true); // old params
        foreach ($items_old as $item) {
            if ($item['type'] == 'image') {
                $tmp = preg_split('/\//', $item['content']);
                if (file_exists(wa()->getDataPath('plugins/uniparams/img/uploaded/', true,
                            'shop').end($tmp)) && end($tmp)) {
                    waFiles::delete(wa()->getDataPath('plugins/uniparams/img/uploaded/', true,
                            'shop').end($tmp), true);
                }
            }
            $list_params->deleteById($item['id']);
        }

        $model->deleteById($data['list_id']);
    }

}