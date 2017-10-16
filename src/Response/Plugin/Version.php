<?php

namespace Maleficarum\Response\Plugin;


class Version extends \Maleficarum\Response\Plugin\AbstractPlugin {

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
        $config = \Maleficarum\Ioc\Container::getDependency('Maleficarum\Config');
        $version = isset($config['global']['version']) ? $config['global']['version'] : null;

        return
            function () use ($version) {
                return $version;
            };
    }
}