<?php

namespace app\admin\command;

use app\admin\model\Admin;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Cell_DataValidation;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;

class GenerateImportCstExample extends Command
{

    protected function configure()
    {
        $this
            ->setName('generateimporctstexample')
            ->setDescription('generateimporctstexample');
    }

    /**
     * 分批查询记录
     * 按记录数划分为不同EXCEL文件
     * 打包文件，删除EXCEL
     * 记录压缩文件位置
     * 注意中文路径的转换，存储文件时转换，存入DB时未转换
     * -- 由于记录条数较少，不做划分EXCEL处理 --
     */
    protected function execute(Input $input, Output $output)
    {
        $cProList      = \app\admin\model\CProject::where('cpdt_status', '=', 1)->column('cpdt_name');
        $developerList = \app\admin\model\Admin::where('status', '=', 'normal')->column('concat(username, \'-\', nickname) as uname');

        $currentExcel      = new PHPExcel();
        $currentExcelSheet = $currentExcel->getActiveSheet();

        $currentExcelSheet->getColumnDimension('D')->setWidth(36);
        $currentExcelSheet->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $currentExcelSheet->getStyle('A1:E1')->getFont()->setBold(true)->setSize(20);
        $currentExcelSheet->getStyle('D')->getAlignment()->setWrapText(true);
        // $currentExcelSheet->freezePane('A1:E1');

        // $currentExcelSheet->getColumnDimensiongetStyle('S' . $lineNo)->getAlignment() ->setWrapText(TRUE);
        $currentExcelSheet->setCellValue('A1', '姓名');
        $currentExcelSheet->setCellValue('B1', '电话');
        $currentExcelSheet->setCellValue('C1', '项目');
        $currentExcelSheet->setCellValue('D1', '客服内容');
        $currentExcelSheet->setCellValue('E1', '网络客服');

        // $output->info("'" . implode("','", $developerList) . "'");
        $cpValidation = $currentExcelSheet->getCell('A1')->getDataValidation();
        $cpValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
            ->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
            ->setAllowBlank(true)
            ->setShowInputMessage(true)
            ->setShowErrorMessage(true)
            ->setShowDropDown(true)
            ->setErrorTitle('输入的值有误')
            ->setError('您输入的值不在下拉框列表内.')
            ->setPromptTitle('客服项目')
            // ->setFormula1("\"'" . implode("', '", $cProList) . "'\"");
            ->setFormula1('"列表项1,列表项2,列表项3"');


        $currentExcelSheet->setDataValidation("C2:C10", $cpValidation);

            // exit;
        // $developerValidation = new PHPExcel_Cell_DataValidation();
        // $developerValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST)
        //     ->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
        //     ->setAllowBlank(true)
        //     ->setShowInputMessage(true)
        //     ->setShowErrorMessage(true)
        //     ->setShowDropDown(true)
        //     ->setErrorTitle('输入的值有误')
        //     ->setError('您输入的值不在下拉框列表内.')
        //     ->setPromptTitle('客服项目')
        //     ->setFormula1("'" . implode("', '", $developerList) . "'");

        //设置A1单元格的选择列表
        // for ($i = 2; $i < 10; $i++) {
        //     $output->info("loop: " . $i);
        //     $currentExcelSheet->getCell('C' . $i)->setDataValidation($cpValidation);

        //     // $currentExcelSheet->setDataValidation('C' . $i, $cpValidation);
        //     // $currentExcelSheet->setDataValidation('E' . $i, $developerValidation);
        // }

        //Excel2007
        $PHPWriter = PHPExcel_IOFactory::createWriter($currentExcel, 'Excel5');
        $PHPWriter->save("/var/tmp/ddd.xls");

    }
}
