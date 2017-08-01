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
                    \Maleficarum\Ioc\Container::register($handlerClass, function ($dep) {
                        if (empty($dep['Maleficarum\Config']['templates']['directory'])) {
                            throw new \RuntimeException('Missing templates path. \Maleficarum\Ioc\Container::get()');
                        }

                        $options = empty($dep['Maleficarum\Config']['templates']['cache_directory']) ? [] : ['cache' => $dep['Maleficarum\Config']['templates']['cache_directory']];
                        $twigLoader = new \Twig_Loader_Filesystem($dep['Maleficarum\Config']['templates']['directory']);
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

            \Maleficarum\Ioc\Container::register('Maleficarum\Response\Http\Response', function ($dep) use ($responseHandler) {
                // add version plugin
                if (isset($dep['Maleficarum\Config'])) {
                    $version = isset($dep['Maleficarum\Config']['global']['version']) ? $dep['Maleficarum\Config']['global']['version'] : null;
                    $responseHandler->addPlugin(
                        'version',
                        function () use ($version) {
                            return $version;
                        }
                    );
                }

                // add profiler plugins on internal envs
                if (isset($dep['Maleficarum\Environment']) && in_array($dep['Maleficarum\Environment']->getCurrentEnvironment(), ['local', 'development', 'staging'])) {
                    $profiler = $dep['Maleficarum\Profiler\Time'] ?? null;
                    is_null($profiler) or $responseHandler->addPlugin(
                        'time_profiler',
                        function () use ($profiler) {
                            $profiler->isComplete() or $profiler->end();

                            return [
                                'exec_time' => $profiler->getProfile(),
                                'req_per_s' => $profiler->getProfile() > 0 ? round(1 / $profiler->getProfile(), 2) : 0,
                            ];
                        }
                    );

                    $profiler = $dep['Maleficarum\Profiler\Database'] ?? null;
                    is_null($profiler) or $responseHandler->addPlugin(
                        'database_profiler',
                        function () use ($profiler) {
                            $count = $exec = 0;
                            foreach ($profiler as $key => $profile) {
                                $count++;
                                $exec += $profile['execution'];
                            }

                            return [
                                'query_count' => $count,
                                'overall_query_exec_time' => $exec,
                            ];
                        }
                    );
                }

                $resp = (new \Maleficarum\Response\Http\Response(new \Phalcon\Http\Response, $responseHandler));

                return $resp;
            });
        }

        // load response object
        \Maleficarum\Ioc\Container::registerDependency('Maleficarum\Response', \Maleficarum\Ioc\Container::get('Maleficarum\Response\Http\Response'));

        return __METHOD__;
    }

    /* ------------------------------------ Class Methods END ------------------------------------------ */

}
