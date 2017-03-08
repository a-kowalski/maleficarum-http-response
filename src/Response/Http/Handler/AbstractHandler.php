<?php
/**
 * This class is a base for all handler classes
 */

namespace Maleficarum\Response\Http\Handler;

abstract class AbstractHandler
{
    /**
     * Internal storage for response body
     *
     * @var string
     */
    protected $body;

    /**
     * Internal storage for response plugins that will add data the response.
     * 
     * @var array
     */
    protected $plugins = [];
    
    /**
     * Add new closure plugin.
     * 
     * @param string   $name
     * @param \Closure $plugin
     * @return \Maleficarum\Response\Http\Handler\AbstractHandler
     */
    public function addPlugin(string $name, \Closure $plugin) : \Maleficarum\Response\Http\Handler\AbstractHandler {
        $this->plugins[$name] = $plugin;
        return $this;
    }
    
    /**
     * Remove existing closure plugin.
     * 
     * @param string   $name
     * @return AbstractHandler
     */
    public function removePlugin(string $name) : \Maleficarum\Response\Http\Handler\AbstractHandler {
        unset($this->plugins[$name]);
        return $this;
    }
    
    /* ------------------------------------ Abstract methods START ------------------------------------- */
    /**
     * Handle response
     *
     * @return \Maleficarum\Response\Http\Handler\AbstractHandler
     */
    abstract public function handle() : \Maleficarum\Response\Http\Handler\AbstractHandler;

    /**
     * Get response content type
     *
     * @return string
     */
    abstract public function getContentType() : string;
    /* ------------------------------------ Abstract methods END --------------------------------------- */

    /* ------------------------------------ Setters & Getters START ------------------------------------ */
    /**
     * Get response body
     *
     * @return string
     */
    public function getBody() : string {
        return $this->body;
    }
    /* ------------------------------------ Setters & Getters END -------------------------------------- */
}
