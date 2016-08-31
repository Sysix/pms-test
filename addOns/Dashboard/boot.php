<?php

$navigation = \PmsOne\Navigation::getNavigation('main-navigation');

$navigation->addPoint(new \PmsOne\Navigation\Point('Dashboard', '/admin/dashboard'));

$app->get('/admin/dashboard', 'AddOn\\Dashboard\\Controller\\Admin\\Dashboard:indexGet')->setName('admin.dashboard');
