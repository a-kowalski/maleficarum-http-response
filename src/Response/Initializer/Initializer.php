<?php
/**
 * This class carries ioc initialization functionality used by this component.
 */
declare (strict_types=1);

namespace Maleficarum\Response\Initializer;

class Initializer {
    /* ------------------------------------ Class Methods START ---------------------------------------- */

    /**
     * This method will initialize the entire package.
     *
     * @param array $opts
     *
     * @return string
     */
    static public function initialize(array $opts = []): string {
        // load default builder if skip not requested
        $builders = $opts['builders'] ?? [];
        is_array($builders) or $builders = [];
        if (!isset($builders['response']['skip'])) {
            $handler = $builders['response']['handler'] ?? 'json';

            $handlerClass = 'Maleficarum\Response\Http\Handler\\';
            switch ($handler) {
                case 'template':
                    $handlerClass .= 'TemplateHandler';
                    \Maleficarum\Ioc\Container::registerBuilder($handlerClass, function ($shares) {
                        if (empty($shares['Maleficarum\Config']['templates']['directory'])) {
                            throw new \RuntimeException('Missing templates path. \Maleficarum\Ioc\Container::get()');
                        }

                        $options = empty($shares['Maleficarum\Config']['templates']['cache_directory']) ? [] : ['cache' => $shares['Maleficarum\Config']['templates']['cache_directory']];
                        $twigLoader = new \Twig_Loader_Filesystem($shares['Maleficarum\Config']['templates']['directory']);
                        $twigEnvironment = new \Twig_Environment($twigLoader, $options);

                        return new \Maleficarum\Response\Http\Handler\TemplateHandler($twigEnvironment);
                    });

                    break;
                case 'json':
                default:
                    $handlerClass .= 'JsonHandler';
                    break;
            }

            /** @var \Maleficarum\Response\Http\Handler\AbstractHandler $responseHandler */
            $responseHandler = \Maleficarum\Ioc\Container::get($handlerClass);

            \Maleficarum\Ioc\Container::registerBuilder('Maleficarum\Response\Http\Response', function ($shares) use ($responseHandler) {
                // add version plugin
                if (isset($shares['Maleficarum\Config'])) {
                    $versionPlugin = \Maleficarum\Ioc\Container::get('Maleficarum\Response\Plugin\Version');
                    $versionPlugin->setConfig($shares['Maleficarum\Config']);
                    $responseHandler->addPlugin($versionPlugin);
                }

                // add profiler plugins on internal envs
                if (isset($shares['Maleficarum\Environment']) && in_array($shares['Maleficarum\Environment']->getCurrentEnvironment(), ['local', 'development', 'staging'])) {

                    $profiler = $shares['Maleficarum\Profiler\Time'] ?? null;
                    if (!is_null($profiler)) {
                        $timeProfilerPlugin = \Maleficarum\Ioc\Container::get('Maleficarum\Response\Plugin\TimeProfiler');
                        $timeProfilerPlugin->setProfiler($profiler);
                        $responseHandler->addPlugin($timeProfilerPlugin);
                    }

                    $profiler = $shares['Maleficarum\Profiler\Database'] ?? null;
                    if (!is_null($profiler)) {
                        $databaseProfilerPlugin = \Maleficarum\Ioc\Container::get('Maleficarum\Response\Plugin\DatabaseProfiler');
                        $databaseProfilerPlugin->setProfiler($profiler);
                        $responseHandler->addPlugin($databaseProfilerPlugin);
                    }
                }

                //add plugins from config
                if (isset($shares['Maleficarum\Config']['response']['plugins']) && is_array($shares['Maleficarum\Config']['response']['plugins'])) {
                    foreach ($shares['Maleficarum\Config']['response']['plugins'] as $pluginClass) {
                        $plugin = \Maleficarum\Ioc\Container::get($pluginClass);
                        if (!$plugin instanceof \Maleficarum\Response\Plugin\AbstractPlugin) {
                            throw new \LogicException('Invalid plugin type specified.');
                        }
                        $responseHandler->addPlugin($plugin);
                    }
                }

                $resp = (new \Maleficarum\Response\Http\Response(new \Phalcon\Http\Response, $responseHandler));

                return $resp;
            });
        }

        // load response object
        \Maleficarum\Ioc\Container::registerShare('Maleficarum\Response', \Maleficarum\Ioc\Container::get('Maleficarum\Response\Http\Response'));

        return __METHOD__;
    }

    /* ------------------------------------ Class Methods END ------------------------------------------ */
}
