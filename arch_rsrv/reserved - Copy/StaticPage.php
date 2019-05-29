<?php

namespace Models;

use App\Config;

use Models\Map\StaticPageTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Connection\ConnectionInterface;
use Cocur\Slugify\Slugify;
use \Core\CustomException;
use Models\Base\StaticPage as BaseStaticPage;

/**
 * Skeleton subclass for representing a row from the 'static_page' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class StaticPage extends BaseStaticPage
{

    protected function setAltUrlByTitleRecursively($try = 1){
        $sluggedTitle = Slugify::create()->slugify($this->getTitle());

        if ($try > 1){
            $sluggedTitle .= ':' . $try;
        }
        $staticPageByTitle = StaticPageQuery::create()->filterById($this->getId(), Criteria::NOT_EQUAL)->findOneByAltUrl($sluggedTitle);
        if (is_null($staticPageByTitle)){
            $this->setAltUrl($sluggedTitle);
        } else {
            $try++;
            return self::setAltUrlByTitleRecursively($try);
        }
    }

    public function preSave(ConnectionInterface $con = null)
    {
        if ($this->isColumnModified(StaticPageTableMap::COL_TITLE)){
            self::setAltUrlByTitleRecursively();
        }
        if (is_callable('parent::preSave')) {
            return parent::preSave($con);
        }
        return true;
    }
}
