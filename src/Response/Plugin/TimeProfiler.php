<?php

namespace Maleficarum\Response\Plugin;

class TimeProfiler extends \Maleficarum\Response\Plugin\AbstractPlugin {

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

        $this->profiler->isComplete() or $this->profiler->end();

        return [
            'exec_time' => $this->profiler->getProfile(),
            'req_per_s' => $this->profiler->getProfile() > 0 ? round(1 / $this->profiler->getProfile(), 2) : 0,
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