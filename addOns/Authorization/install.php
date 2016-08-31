<?php

$users = new \AddOn\Authorization\Model\User();
$users->getMapper()->migrate();

$clients = new \AddOn\Authorization\Model\Client();
$clients->getMapper()->migrate();

$accessTokens = new \AddOn\Authorization\Model\AccessToken();
$accessTokens->getMapper()->migrate();

$authorizationCodes = new \AddOn\Authorization\Model\AuthorizationCode();
$authorizationCodes->getMapper()->migrate();

$refreshTokens = new \AddOn\Authorization\Model\RefreshToken();
$refreshTokens->getMapper()->migrate();

$scopes = new \AddOn\Authorization\Model\Scope();
$scopes->getMapper()->migrate();

$jwts = new \AddOn\Authorization\Model\Jwt();
$jwts->getMapper()->migrate();