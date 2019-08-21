<?php

namespace app\admin\controller\wm;

use app\common\controller\Backend;

use think\Controller;
use think\Request;
use think\DB;
use think\File;
/**
 *  导入产品
 */
class Import extends Backend
{
	public function index(){

		return $this->view->fetch();
	}

	public function getFiles(Request $request){
		vendor('PHPExcel.PHPExcel');
		vendor('PHPExcel.PHPExcel.IOFactory');

		$unitData = db('unit')->column('name,id');
		$depotData = db('depot')->column('name,id');
		$file = request()->file('wmfile');
		// $file = file('files');
		// var_dump($depotData);
        if(empty($file)){
            $this->error('文件不存在');
        }
        //保存文件
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads'. DS . 'wmimport');
        if(empty($info)){
            $this->error($file->getError());
        }
        //文件路径
        $filepath =   ROOT_PATH.'public' . DS . 'uploads'. DS . 'wmimport'. DS .$info->getSaveName();
        $filename = "/public/uploads/wmimport/".str_replace('\\','/',$info->getSaveName());
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        try{
            //$objReader = new \PHPExcel_Reader_Excel5();//注意和导出的类不一样哦  xls文件
            $objReader = $extension=='xlsx'?new \PHPExcel_Reader_Excel2007:new \PHPExcel_Reader_Excel5;//注意和导出的类不一样哦  xlsx文件
            $objPHPExcel = $objReader->load($filepath); //上传的文件，或者是指定的文件
        }catch (\Exception $e){
            $this->error($e->getMessage());
        }

        $sheetAllData = $objPHPExcel->getSheet(0)->toArray();
        // $highestRow = $sheet->getHighestRow(); // 取得总行数
        array_shift($sheetAllData);		// 去除第一行

        $sqlData=[];
        foreach ($sheetAllData as $k => $v) {
        	$sqlData[$k]['pro_name'] = $v[0];
        	$sqlData[$k]['pro_code'] = $v[1];
        	$sqlData[$k]['pro_spell'] = $v[2];
        	$sqlData[$k]['pro_unit'] = $unitData[$v[3]];
        	$sqlData[$k]['pro_spec'] = $v[4];
        	$sqlData[$k]['pro_cost'] = $v[5];
        	$sqlData[$k]['pro_amount'] = $v[6];
        	$sqlData[$k]['depot_id'] = $depotData[$v[7]];;
        	$sqlData[$k]['pro_type'] = $v[8];

        	$sqlData[$k]['createtime'] = time();
        	$sqlData[$k]['dept_id'] = 0;			//结算科室设置为0
        	$sqlData[$k]['pro_fee_type'] = 0;  		//费用类型设置为0
        	$sqlData[$k]['stock_low'] = 0;  		//库存下限设置为0
        }
        // var_dump($sqlData);
        Db::startTrans();
// $insertRes='1';,url('wm/import'),url('wm/import')
        $insertRes = db('project_copy')->insertAll($sqlData);

        if($insertRes){
        	Db::commit();
            $this->success('导入成功！');
        }else{
        	Db::rollback();
            $this->error('导入失败！');
        }


	}
}