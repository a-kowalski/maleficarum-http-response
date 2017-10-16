<?php

namespace Maleficarum\Response\Plugin;

class DatabaseProfiler extends \Maleficarum\Response\Initializer\AbstractPlugin {

    /**
     * Fetch plugin name.
     *
     * @return string
     */
    public function getName(): string {
        return 'database_profiler';
    }

    /**
     * Execute plugin logic.
     *
     * @return mixed
     */
    public function execute() {
        $profiler = \Maleficarum\Ioc\Container::getDependency('Maleficarum\Profiler\Database');

        return
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
            };
    }
}