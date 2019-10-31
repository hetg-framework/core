<?php

namespace Hetg\Framework\Response;


class JsonResponse extends Response
{

    public function __construct($content = [], int $code = 200, array $headers = [])
    {
        $headers['Content-Type'] = ['application/json'];

        parent::__construct(json_encode($content), $code, $headers);
    }

}