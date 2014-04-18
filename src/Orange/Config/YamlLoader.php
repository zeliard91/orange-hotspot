<?php
namespace Orange\Config;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

class YamlLoader extends FileLoader
{
    public function load($resource, $type = null)
    {
        $configValues = Yaml::parse($resource);

        if (!is_array($configValues)
            || !isset($configValues['parameters'])
            || !isset($configValues['parameters']['login'])
            || !isset($configValues['parameters']['pass'])
        ) {
            throw new \Exception("Unattended config values");
        }

        return $configValues['parameters'];
    }

    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo(
            $resource,
            PATHINFO_EXTENSION
        );
    }
}
