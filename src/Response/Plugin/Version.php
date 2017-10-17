<?php

namespace Maleficarum\Response\Plugin;


class Version extends \Maleficarum\Response\Plugin\AbstractPlugin {

    /**
     * @var \Maleficarum\Config\AbstractConfig $config
     */
    private $config;

    /**
     * Fetch plugin name.
     *
     * @return string
     */
    public function getName(): string {
        return 'version';
    }

    /**
     * Execute plugin logic.
     *
     * @return mixed
     */
    public function execute() {
        $version = isset($this->config['global']['version']) ? $this->config['global']['version'] : null;

        return $version;
    }

    /**
     * @param \Maleficarum\Config\AbstractConfig $config
     *
     * @return $this
     */
    public function setConfig(\Maleficarum\Config\AbstractConfig $config) {
        $this->config = $config;

        return $this;
    }
}