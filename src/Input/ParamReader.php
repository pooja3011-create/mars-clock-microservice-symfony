<?php
declare(strict_types = 1);

namespace App\Input;


use Symfony\Component\HttpFoundation\Request;
use DateTimeZone;


class ParamReader
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function getDateTime(): \DateTime
    {
        if (!$dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $this->request->request->get('datetime'),new DateTimeZone('UTC'))) {
            throw new \InvalidArgumentException('Given datetime is not valid!');
        }

        return $dateTime;
    }
}
