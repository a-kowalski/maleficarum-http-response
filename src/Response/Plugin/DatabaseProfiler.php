<?php

namespace Maleficarum\Response\Plugin;

class DatabaseProfiler extends \Maleficarum\Response\Plugin\AbstractPlugin {
    /**
     * @var \Maleficarum\Profiler\Database\Generic $profiler
     */
    private $profiler;

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
        $count = $exec = 0;
        foreach ($this->profiler as $key => $profile) {
            $count++;
            $exec += $profile['execution'];
        }

        return [
            'query_count' => $count,
            'overall_query_exec_time' => $exec,
        ];
    }

    /**
     * @param \Maleficarum\Profiler\Database\Generic $profiler
     *
     * @return $this
     */
    public function setProfiler(\Maleficarum\Profiler\Database\Generic $profiler) {
        $this->profiler = $profiler;

        return $this;
    }
}