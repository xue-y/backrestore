<?php
/**
 * Created by PhpStorm.
 * User: Think
 * Date: 2019/8/17
 * Time: 20:24
 * 优化修复表
 */

namespace src;


class OptimizeRepair extends Common
{
    /**
     * OptimizeRepair constructor.
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        parent::__construct($config);
    }

    /**
     * optimize
     * @todo 优化表
     * @param $table
     */
    public function optimize($table)
    {
        $result=$this->db->optimizeTable($table);
        ob_flush();
        flush();
        foreach ($result as $k=>$v){
            echo $v["Table"].' '.$v["Msg_text"].'<br/>';
        }
    }

    /**
     * repair
     * @todo 修复表
     * @param $table
     */
    public function repair($table)
    {
        $result=$this->db->repairTable($table);
        ob_flush();
        flush();
        foreach ($result as $k=>$v){
            echo $v["Table"].' '.$v["Msg_text"].'<br/>';
        }
    }
}