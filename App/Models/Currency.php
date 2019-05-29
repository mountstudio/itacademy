<?php

namespace Models;

use Models\Base\Currency as BaseCurrency;
use Propel\Runtime\ActiveQuery\Criteria;

/**
 * Skeleton subclass for representing a row from the 'currency' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Currency extends BaseCurrency
{
    public function getCurrentRate(self $currency = null)
    {
        if (is_null($currency)) {
            $defaultCurrency = ConfigQuery::create()->findOneByKey('default_currency_id');
            $currency = CurrencyQuery::create()->findPk(intval($defaultCurrency->getValue()));
        }
        $currencyRate = CurrencyRateQuery::create()
            ->filterByCurrentDefaultCurrency($currency)
            ->filterByCurrentToCurrency($this)
            ->orderByCreatedAt(Criteria::DESC)
            ->findOne();

        if (is_null($currencyRate)){
            return 1.0;
        } else {
            $currentValue = $currencyRate->getRate();
            return $currentValue;
        }

    }
}