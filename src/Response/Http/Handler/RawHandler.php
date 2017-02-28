<?php
/**
 * This class provides functionality of handling raw response
 */

namespace Maleficarum\Response\Http\Handler;

class RawHandler extends \Maleficarum\Response\Http\Handler\AbstractHandler
{
    /**
     * Internal storage for response content-type
     *
     * @var string
     */
    private $contentType;

    /* ------------------------------------ Magic methods START ---------------------------------------- */
    /**
     * RawHandler constructor.
     *
     * @param string $contentType
     */
    public function __construct(string $contentType = 'text/html') {
        $this->contentType = $contentType;
    }
    /* ------------------------------------ Magic methods END ------------------------------------------ */

    /* ------------------------------------ AbstractHandler methods START ------------------------------ */
    /**
     * Handle response
     *
     * @see \Maleficarum\Response\Http\Handler\AbstractHandler::handle()
     *
     * @param string $data
     *
     * @return \Maleficarum\Response\Http\Handler\AbstractHandler
     */
    public function handle(string $data = '') : \Maleficarum\Response\Http\Handler\AbstractHandler {
        // initialize response content
        $this->body = $data;

        return $this;
    }
    /* ------------------------------------ AbstractHandler methods END -------------------------------- */

    /* ------------------------------------ Setters & Getters START ------------------------------------ */
    /**
     * Get contentType
     *
     * @return string
     */
    public function getContentType() : string {
        return $this->contentType;
    }

    /**
     * Set contentType
     *
     * @param string $contentType
     *
     * @return \Maleficarum\Response\Http\Handler\RawHandler
     */
    public function setContentType(string $contentType) : \Maleficarum\Response\Http\Handler\RawHandler {
        $this->contentType = $contentType;

        return $this;
    }
    /* ------------------------------------ Setters & Getters END -------------------------------------- */
}
