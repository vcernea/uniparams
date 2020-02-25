<?php

class shopUniparamsPluginBackendGetlistAction extends waViewAction {

    public function execute() {
        $model = new shopUniparamsListsModel();

        $kids = array();
        $list = $model->select('*')->where('parent_id IS NULL')->order("front_index ASC")->fetchAll();
        foreach($list as $item => $val) {
//            $kids[$val['id']] = array();
            if ($val['is_group'] == 1) {
//                waLog::dump($val, 'tt.log');
                $kids = $model->select("*")->where('parent_id="'.$val['id'].'"')->order("front_index ASC")->fetchAll();

                $list[$item]['kids'] = array();
                $list[$item]['kids'] = $kids;
            }

//            waLog::dump($list[$item], 'tt.log');
        }
//        waLog::dump($list, 'tt.log');
        $this->view->assign('lists', $list);
    }

}