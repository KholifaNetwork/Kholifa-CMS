<?php
/**
 * @package   Kholifa CMS
 */


namespace including;


class Ecommerce
{
    public function subscriptionPaymentUrl($options)
    {
        if (empty($options['item'])) {
            throw new \including\Exception('"item" setting is missing in subscriptionPaymentUrl function');
        }
        $paymentUrl = knJob('knSubscriptionPaymentUrl', $options);
        return $paymentUrl;
    }

    public function subscriptionCancelUrl($options)
    {
        if (empty($options['item'])) {
            throw new \including\Exception('"item" setting is missing in subscriptionCancelUrl function');
        }
        $cancelUrl = knJob('knSubscriptionCancelUrl', $options);
        return $cancelUrl;
    }

    public function paymentUrl($options)
    {
        if (empty($options['id'])) {
            throw new \including\Exception('"id" setting is missing in paymentUrl function');
        }
        if (empty($options['price'])){
            throw new \including\Exception('"price" setting is missing in paymentUrl function');
        }
        if (empty($options['currency'])) {
            throw new \including\Exception('"currency" setting is missing in paymentUrl function');
        }
        $paymentUrl = knJob('knPaymentUrl', $options);
        return $paymentUrl;
    }

}
