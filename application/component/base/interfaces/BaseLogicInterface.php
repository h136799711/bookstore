<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-04 11:07
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace app\component\base\interfaces;


use app\component\tp5\model\BaseModel;
use by\component\paging\vo\PagingParams;
use think\Exception;

interface BaseLogicInterface
{

    /**
     * 求和统计
     * @param $map
     * @param $field
     * @return float|int
     */
    public function sum($map, $field);

    /**
     * get model
     * @return BaseModel
     */
    public function getModel();


    /**
     * 数量统计
     * @param $map
     * @param bool $field
     * @return int|string
     */
    public function count($map, $field = false);

    /**
     * 禁用
     * 必须有status字段 ，0 为禁用状态
     * @param $map
     * @return bool
     */
    public function disable($map);

    /**
     * 保存
     * @param $map
     * @param $entity
     * @return string 错误信息或更新条数
     */
    public function save($map, $entity);

    /**
     * 启用
     * 必须有status字段，1 为启用状态
     * @param $map
     * @return bool
     */
    public function enable($map);

    /**
     * 假删除
     * 必须有status字段，且 －1 为删除状态
     * @param $map
     * @return bool
     */
    public function pretendDelete($map);

    /**
     * 根据id保存数据
     * @param $id
     * @param $entity
     * @return array | bool
     */
    public function saveByID($id, $entity);

    /**
     * 数字类型字段有效
     * @param $map array 条件
     * @param $field string 更改字段
     * @param float|int $cnt float 增加的值
     * @return integer 返回影响记录数 或 错误信息
     */
    public function setInc($map, $field, $cnt = 1);

    /**
     * 数字类型字段有效,不允许小于0,维护字段最小为0,金额等敏感类型不适用
     * @param $map array 条件
     * @param $field string 更改字段
     * @param $cnt int 减少的值
     * @return integer 返回影响记录数 或 错误信息
     */
    public function setDecCantZero($map, $field, $cnt = 1);

    /**
     * 数字类型字段有效
     * @param $map array 条件
     * @param $field string 更改字段
     * @param $cnt int 减少的值 减少的值
     * @return integer|string 返回影响记录数 或 错误信息
     */
    public function setDec($map, $field, $cnt = 1);

    /**
     * 批量更新，仅根据主键来
     * @param $entity
     * @return array
     */
    public function saveAll($entity);

    /**
     * 获取数据find
     * @param $map
     * @param bool $order
     * @param bool $field
     * @return array|false|null|object|\PDOStatement|string|\think\Model
     */
    public function getInfo($map, $order = false, $field = false);

    /**
     * 删除
     * @map 条件
     * @param $map
     * @return int
     */
    public function delete($map);


    /**
     * 批量删除
     * @param $data
     * @return int
     */
    public function bulkDelete($data);

    /**
     * add 添加
     * 支持 类传入
     * @param $entity
     * @param $pk string 主键
     * @return bool
     * @throws Exception
     */
    public function add($entity, $pk = 'id');

    /**
     * 批量插入
     * @param array $list 数组
     * @return array
     */
    public function addAll($list);

    /**
     * query 不分页
     * @param $map array 查询条件
     * @param $order boolean|string 排序条件
     * @param $fields boolean|string 只获取指定字段
     * @return array
     */
    public function queryNoPaging($map = null, $order = false, $fields = false);


    /**
     * query
     * @param array $map 查询条件
     * @param PagingParams $page 分页参数
     * @param bool $order 排序参数
     * @param bool $fields
     * @return array
     */
    public function query($map, PagingParams $page, $order = false, $fields = false);

    /**
     * query
     * @param array $map
     * @param PagingParams $page
     * @param bool $order
     * @param bool|array $params
     * @param bool $fields
     * @return Paginator
     */
    public function queryWithPagingHtml($map, PagingParams $page, $order = false, $params = [], $fields = false);
}