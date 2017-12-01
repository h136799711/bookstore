<?php
/**
 * 注意：本内容仅限于博也公司内部传阅,禁止外泄以及用于其他的商业目的
 * @author    hebidu<346551990@qq.com>
 * @copyright 2017 www.itboye.com Boye Inc. All rights reserved.
 * @link      http://www.itboye.com/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * Revision History Version
 ********1.0.0********************
 * file created @ 2017-11-21 10:21
 *********************************
 ********1.0.1********************
 *
 *********************************
 */

namespace app\component\bs\params;

use by\component\bookstore\v1\entity\BookEntity;
use by\infrastructure\helper\Object2DataArrayHelper;
use by\infrastructure\interfaces\ObjectToArrayInterface;

/**
 * Class BsBookSearchParams
 * 书籍查询参数
 * @package app\component\bs\params
 */
class BsBookSearchParams implements ObjectToArrayInterface
{
    public function toArray()
    {
        return Object2DataArrayHelper::getDataArrayFrom($this);
    }

    public function getMap()
    {
        $map = [];
        if (!empty($this->penName)) {
            $map['author_name'] = ['like', '%' . $this->penName . '%'];
        }
        if (!empty($this->bookName)) {
            $map['title'] = ['like', '%' . $this->bookName . '%'];
        }

        if (!empty($this->bookCategoryId)) {
            $map['cate_id'] = $this->bookCategoryId;
        }

        if ($this->bookState == BookEntity::STATE_END || $this->bookState == BookEntity::STATE_Serialize) {
            $map['state'] = $this->bookState;
        }

        return $map;
    }

    private $penName;
    private $bookName;
    private $bookState;
    private $bookCategoryId;

    // construct
    public function __construct()
    {
        $this->setBookCategoryId(0);
        $this->setBookName('');
        $this->setBookState(BookEntity::STATE_Unknown);
    }

    /**
     * @return mixed
     */
    public function getPenName()
    {
        return $this->penName;
    }

    /**
     * @param mixed $penName
     */
    public function setPenName($penName)
    {
        $this->penName = $penName;
    }

    /**
     * @return mixed
     */
    public function getBookName()
    {
        return $this->bookName;
    }

    /**
     * @param mixed $bookName
     */
    public function setBookName($bookName)
    {
        $this->bookName = $bookName;
    }

    /**
     * @return mixed
     */
    public function getBookState()
    {
        return $this->bookState;
    }

    /**
     * @param mixed $bookState
     */
    public function setBookState($bookState)
    {
        $this->bookState = $bookState;
    }

    /**
     * @return mixed
     */
    public function getBookCategoryId()
    {
        return $this->bookCategoryId;
    }

    /**
     * @param mixed $bookCategoryId
     */
    public function setBookCategoryId($bookCategoryId)
    {
        $this->bookCategoryId = $bookCategoryId;
    }
}