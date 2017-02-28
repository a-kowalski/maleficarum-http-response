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
