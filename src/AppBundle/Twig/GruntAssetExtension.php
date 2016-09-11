<?php

namespace AppBundle\Twig;

use Twig_Environment;

/**
 * Class GruntAssetExtension
 */
class GruntAssetExtension extends \Twig_Extension
{
    protected $assets;
    protected $twig;

    /**
     * @param Twig_Environment $twig
     * @param string           $versionsFilePath
     */
    public function __construct(Twig_Environment $twig, $versionsFilePath)
    {
        $this->assets = [];
        $this->twig = $twig;

        if (file_exists($versionsFilePath)) {
            $data = file_get_contents($versionsFilePath);
            $assets = @json_decode($data, true);
            if (is_array($assets)) {
                foreach ($assets as $asset) {
                    if (file_exists($asset['versionedPath'])) {
                        $this->assets[$asset['originalPath']] = $asset;
                    }
                }
            }
        }
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('grunt_asset', array($this, 'gruntAsset')),
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'grunt_asset_extension';
    }

    /**
     * @param string $string
     * @return string
     */
    public function gruntAsset($string)
    {
        $string = ltrim($string, '/');
        if (array_key_exists($string, $this->assets)) {
            $string = $this->assets[$string]['versionedPath'];
        }

        return $this->twig->getExtension('asset')->getAssetUrl($string);
    }
}
