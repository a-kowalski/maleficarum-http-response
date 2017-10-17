<?php

namespace Maleficarum\Response\Plugin;

class TimeProfiler extends \Maleficarum\Response\Initializer\AbstractPlugin {

    /**
     * @var \Maleficarum\Profiler\Time $profiler
     */
    private $profiler;

    /**
     * Fetch plugin name.
     *
     * @return string
     */
    public function getName(): string {
        return 'time_profiler';
    }

    /**
     * Execute plugin logic.
     *
     * @return mixed
     */
    public function execute() {
        $profiler = \Maleficarum\Ioc\Container::getDependency('Maleficarum\Profiler\Time');

        $profiler->isComplete() or $profiler->end();

        return [
            'exec_time' => $profiler->getProfile(),
            'req_per_s' => $profiler->getProfile() > 0 ? round(1 / $profiler->getProfile(), 2) : 0,
        ];
    }

    /**
     * @param \Maleficarum\Profiler\Time $profiler
     *
     * @return $this
     */
    public function setProfiler(\Maleficarum\Profiler\Time $profiler) {
        $this->profiler = $profiler;

        return $this;
    }
}