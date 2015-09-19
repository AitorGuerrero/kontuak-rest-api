<?php

namespace AppBundle\Controller\Exception;

class IncorrectResourceId extends \Exception
{
    protected $message = 'The id of the resource must be the same of the URI';
}