<?php
/**
 * Mageplaza
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Mageplaza.com license that is
 * available through the world-wide-web at this URL:
 * https://www.mageplaza.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageplaza
 * @package     Mageplaza_RewardPointsGraphQl
 * @copyright   Copyright (c) Mageplaza (https://www.mageplaza.com/)
 * @license     https://www.mageplaza.com/LICENSE.txt
 */

declare(strict_types=1);

namespace HungRewardPoint\CustomRewardPoint\Model\Resolver;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\QuoteGraphQl\Model\Cart\GetCartForUser;
/**
 * Class RewardSegments
 * @package Mageplaza\RewardPointsGraphQl\Model\Resolver\Customer
 */
class RewardQuote implements ResolverInterface
{
    protected $collection;
    protected $totalsCollector;
    protected $_checkoutSession;
    protected $totalcart;

    /**
     * RewardSegments constructor.
     *
     * @param CartTotalRepositoryInterface $cartTotalRepository
     */
    public function __construct(
        \Magento\Quote\Model\Quote $collection,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Checkout\Model\Cart $totalcart,
        \Magento\Quote\Model\Quote\TotalsCollector $totalsCollector
    )
    {
        $this->collection = $collection;
        $this->_checkoutSession = $checkoutSession;
        $this->totalcart = $totalcart;
        $this->totalsCollector = $totalsCollector;
    }

    /**
     * @param Field $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     *
     * @return array[]
     * @throws GraphQlNoSuchEntityException
     * @throws LocalizedException
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        // if (!isset($value['model'])) {
        //     throw new LocalizedException(__('"model" value should be specified'));
        // }
        // $quote = $value['model'];
        // $quote->setCartFixedRules([]);
        // $cartTotals = $this->totalsCollector->collectQuoteTotals($quote);
        // $currency = $quote->getQuoteCurrencyCode();
        $getCurrentQuote = $this->_checkoutSession->getQuote();
        $rewardcheckout = [];
        $rewardcheckout = ['mp_reward_earn'=> $getCurrentQuote->getMpRewardEarn(),
                            'mp_reward_spent'=> $getCurrentQuote->getMpRewardSpent(),
                            'mp_reward_discount'=> $getCurrentQuote->getMpRewardDiscount()];
        // var_dump($rewardcheckout);
        return $rewardcheckout;
    }
}
