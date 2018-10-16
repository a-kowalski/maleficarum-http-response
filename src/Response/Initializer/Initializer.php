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
                    \Maleficarum\Ioc\Container::registerBuilder($handlerClass, function ($dep) {
                        if (empty($dep['Maleficarum\Config']['templates']['directory'])) {
                            throw new \RuntimeException('Missing templates path. \Maleficarum\Ioc\Container::get()');
                        }

                        $options = empty($dep['Maleficarum\Config']['templates']['cache_directory']) ? [] : ['cache' => $dep['Maleficarum\Config']['templates']['cache_directory']];
                        $twigLoader = new \Twig_Loader_Filesystem($dep['Maleficarum\Config']['templates']['directory']);
                        $twigEnvironment = new \Twig_Environment($twigLoader, $options);

                        if (isset($dep['Maleficarum\Config']['twig_extensions'])) {
                            foreach($dep['Maleficarum\Config']['twig_extensions'] as $extensionClassName){
                                $twigEnvironment->addExtension(new $extensionClassName());
                            }
                        }

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

            \Maleficarum\Ioc\Container::registerBuilder('Maleficarum\Response\Http\Response', function ($dep) use ($responseHandler) {
                // add version plugin
                if (isset($dep['Maleficarum\Config'])) {
                    $versionPlugin = \Maleficarum\Ioc\Container::get('Maleficarum\Response\Plugin\Version');
                    $versionPlugin->setConfig($dep['Maleficarum\Config']);
                    $responseHandler->addPlugin($versionPlugin);
                }

                // add profiler plugins on internal envs
                if (isset($dep['Maleficarum\Environment']) && in_array($dep['Maleficarum\Environment']->getCurrentEnvironment(), ['local', 'development', 'staging'])) {

                    $profiler = $dep['Maleficarum\Profiler\Time'] ?? null;
                    if (!is_null($profiler)) {
                        $timeProfilerPlugin = \Maleficarum\Ioc\Container::get('Maleficarum\Response\Plugin\TimeProfiler');
                        $timeProfilerPlugin->setProfiler($profiler);
                        $responseHandler->addPlugin($timeProfilerPlugin);
                    }

                    $profiler = $dep['Maleficarum\Profiler\Database'] ?? null;
                    if (!is_null($profiler)) {
                        $databaseProfilerPlugin = \Maleficarum\Ioc\Container::get('Maleficarum\Response\Plugin\DatabaseProfiler');
                        $databaseProfilerPlugin->setProfiler($profiler);
                        $responseHandler->addPlugin($databaseProfilerPlugin);
                    }
                }

                //add plugins from config
                if (isset($dep['Maleficarum\Config']['response']['plugins']) && is_array($dep['Maleficarum\Config']['response']['plugins'])) {
                    foreach ($dep['Maleficarum\Config']['response']['plugins'] as $pluginClass) {
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
