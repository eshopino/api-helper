<?php
namespace Eshopino\Api;


class ApiException extends \RuntimeException
{

}


class AuthorizationException extends ApiException
{

}


class InvalidRequestException extends ApiException
{

}


class ValidationException extends \RuntimeException
{


}
