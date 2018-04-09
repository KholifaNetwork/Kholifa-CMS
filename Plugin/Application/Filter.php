<?php


namespace Plugin\Application;


class Filter
{
    /**
     * @param \content\Response $response
     * @return mixed
     */
    public static function knSendResponse($response)
    {
        // modify response before sending
        return $response;
    }
}
