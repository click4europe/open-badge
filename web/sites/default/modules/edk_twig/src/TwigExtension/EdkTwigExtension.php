<?php

namespace Drupal\edk_twig\TwigExtension;

use Drupal\Core\Url;
use Drupal\file\Entity\File;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class EdkTwigExtension extends AbstractExtension
{
    public function getName()
    {
        return 'edk_twig';
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('edk_file', [$this, 'edk_file']),
            new TwigFunction('edk_link', [$this, 'edk_link']),
        ];
    }

    public function edk_file($fid = null)
    {
        $path = '';
        if (is_numeric($fid)) {
            $file = File::load($fid);
            if ($file) {
                $path = \Drupal::service('file_url_generator')->generateAbsoluteString($file->getFileUri());
            }
        }
        return $path;
    }

    public function edk_link($link = '')
    {
        $path = '';
        if (is_string($link) && strlen($link)) {
            $options = ['absolute' => TRUE];
            $path = Url::fromUri($link, $options)->toString();
        }
        return $path;
    }
}