<?php
/**
 * This class provides functionality of handling raw response
 */
declare (strict_types=1);

namespace Maleficarum\Response\Http\Handler;

class RawHandler extends \Maleficarum\Response\Http\Handler\AbstractHandler {
    /* ------------------------------------ Class Property START --------------------------------------- */

    /**
     * Internal storage for response content-type
     *
     * @var string
     */
    private $contentType;

    /* ------------------------------------ Class Property END ----------------------------------------- */

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

    /* ------------------------------------ Class Methods START ---------------------------------------- */

    /**
     * @see \Maleficarum\Response\Http\Handler\AbstractHandler::handle()
     */
    public function handle(string $data = ''): \Maleficarum\Response\Http\Handler\AbstractHandler {
        // initialize response content
        $this->body = $data;

        return $this;
    }

    /* ------------------------------------ Class Methods END ------------------------------------------ */

    /* ------------------------------------ Setters & Getters START ------------------------------------ */

    /**
     * Get contentType
     *
     * @return string
     */
    public function getContentType(): string {
        return $this->contentType;
    }

    /**
     * Set contentType
     *
     * @param string $contentType
     *
     * @return \Maleficarum\Response\Http\Handler\RawHandler
     */
    public function setContentType(string $contentType): \Maleficarum\Response\Http\Handler\RawHandler {
        $this->contentType = $contentType;

        return $this;
    }

    /* ------------------------------------ Setters & Getters END -------------------------------------- */
}
