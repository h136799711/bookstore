<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-12-06 17:08
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace app\domain\dto\book;


use app\domain\dto\BaseDto;

/**
 * Class BookDetailDto
 * 详情
 * @package app\domain\dto\book
 * @Dto
 */
class BookDetailDto extends BaseDto
{
    /**
     * @error_msg
     * @require
     * @var
     */
    private $id;

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

}