<?php
/**
 * Created by PhpStorm.
 * User: Andrianina OELI
 * Date: 11/11/2016
 * Time: 17:48
 */

namespace App\Helpers;

class TwigBaseExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('activeClass', [$this, 'activeClass'], ['needs_context' => true])
        ];
    }

    function activeClass(array $context, $page, $name)
    {
        return isset($page) && $page === $name ? 'active' : '';
    }
}