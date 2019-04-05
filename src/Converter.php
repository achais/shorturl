<?php
/**
 * Created by PhpStorm.
 * User: achais
 * Date: 2019-04-05
 * Time: 16:09
 */

namespace Achais\ShortUrl;


use Achais\ShortUrl\Exceptions\NoGatewayAvailableException;

class Converter
{
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILURE = 'failure';

    protected $shortUrl;

    public function __construct(ShortUrl $shortUrl)
    {
        $this->shortUrl = $shortUrl;
    }

    public function shorten($url, array $gateways = [])
    {
        $results = [];
        $isSuccessful = false;
        foreach ($gateways as $gateway => $config) {
            try {
                $results[$gateway] = [
                    'gateway' => $gateway,
                    'status' => self::STATUS_SUCCESS,
                    'result' => $this->shortUrl->gateway($gateway)->shorten($url, $config),
                ];
                $isSuccessful = true;
                break;
            } catch (\Exception $e) {
                $results[$gateway] = [
                    'gateway' => $gateway,
                    'status' => self::STATUS_FAILURE,
                    'exception' => $e,
                ];
            } catch (\Throwable $e) {
                $results[$gateway] = [
                    'gateway' => $gateway,
                    'status' => self::STATUS_FAILURE,
                    'exception' => $e,
                ];
            }
        }
        if (!$isSuccessful) {
            throw new NoGatewayAvailableException($results);
        }
        return $results;
    }

    public function expand($url, array $gateways = [])
    {
        $results = [];
        $isSuccessful = false;
        foreach ($gateways as $gateway => $config) {
            try {
                $results[$gateway] = [
                    'gateway' => $gateway,
                    'status' => self::STATUS_SUCCESS,
                    'result' => $this->shortUrl->gateway($gateway)->expand($url, $config),
                ];
                $isSuccessful = true;
                break;
            } catch (\Exception $e) {
                $results[$gateway] = [
                    'gateway' => $gateway,
                    'status' => self::STATUS_FAILURE,
                    'exception' => $e,
                ];
            } catch (\Throwable $e) {
                $results[$gateway] = [
                    'gateway' => $gateway,
                    'status' => self::STATUS_FAILURE,
                    'exception' => $e,
                ];
            }
        }
        if (!$isSuccessful) {
            throw new NoGatewayAvailableException($results);
        }
        return $results;
    }
}
