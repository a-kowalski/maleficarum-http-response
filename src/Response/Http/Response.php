<?php
/**
 * This class provides functionality of response rendering in HTTP context
 */

namespace Maleficarum\Response\Http;

class Response extends \Maleficarum\Response\AbstractResponse
{
    /**
     * Internal storage for response object
     *
     * @var \Phalcon\Http\Response
     */
    private $response;

    /**
     * Internal storage for response handler object
     *
     * @var \Maleficarum\Response\Http\Handler\AbstractHandler
     */
    private $handler;

    /* ------------------------------------ Magic methods START ---------------------------------------- */
    /**
     * Response constructor.
     *
     * @param \Phalcon\Http\Response $response
     * @param \Maleficarum\Response\Http\Handler\AbstractHandler $handler
     */
    public function __construct(\Phalcon\Http\Response $response, \Maleficarum\Response\Http\Handler\AbstractHandler $handler) {
        $this->response = $response;
        $this->handler = $handler;

        // set default status code and message
        $this->setStatusCode(\Maleficarum\Response\Http\Status::STATUS_CODE_200);
    }
    /* ------------------------------------ Magic methods END ------------------------------------------ */

    /* ------------------------------------ Response methods START ------------------------------------- */
    /**
     * Render HTTP response using currently injected handler
     *
     * @param array ...$arguments
     *
     * @return \Maleficarum\Response\AbstractResponse
     */
    public function render(...$arguments) : \Maleficarum\Response\AbstractResponse {
        // forward render action to the handler
        call_user_func_array([$this->handler, 'render'], $arguments);

        return $this;
    }

    /**
     * Output HTTP response object
     *
     * @return \Maleficarum\Response\AbstractResponse
     */
    public function output() : \Maleficarum\Response\AbstractResponse {
        $contentType = $this->handler->getContentType();
        $body = $this->handler->getBody();

        // add typical response headers
        $this
            ->response
            ->setHeader('Content-Type', $contentType);

        // send the response
        $this
            ->response
            ->setContent($body)
            ->send();

        return $this;
    }

    /**
     * Redirect the request to a new URI
     *
     * @param string $url
     * @param bool $immediate
     *
     * @return \Maleficarum\Response\Http\Response
     */
    public function redirect(string $url, bool $immediate = true) : \Maleficarum\Response\Http\Response {
        // send redirect header to the response object
        $this->response->redirect($url);

        // stop execution and redirect immediately
        if ($immediate) {
            $this->response->send();
            $this->terminate();
        }

        return $this;
    }

    /**
     * Add a new header
     *
     * @param string $name
     * @param string $value
     *
     * @return \Maleficarum\Response\Http\Response
     */
    public function addHeader(string $name, string $value) : \Maleficarum\Response\Http\Response {
        $this->response->setHeader($name, $value);

        return $this;
    }

    /**
     * Clear all headers
     *
     * @return \Maleficarum\Response\Http\Response
     */
    public function clearHeaders() : \Maleficarum\Response\Http\Response {
        $this->response->resetHeaders();

        return $this;
    }

    /**
     * Detect if the response has already been sent
     *
     * @return bool
     */
    public function isSent() : bool {
        return $this->response->isSent();
    }

    /**
     * This method will set the current status code and a RFC recommended status message for that code. Setting an unsupported HTTP status code will result in an exception
     *
     * @param int $code
     *
     * @return \Maleficarum\Response\AbstractResponse
     */
    public function setStatusCode(int $code) : \Maleficarum\Response\AbstractResponse {
        $message = \Maleficarum\Response\Http\Status::getMessageForStatus($code);

        $this->response->setStatusCode($code, $message);

        return $this;
    }

    /**
     * Terminate execution
     */
    protected function terminate() {
        exit;
    }
    /* ------------------------------------ Response methods END --------------------------------------- */

    /* ------------------------------------ Setters & Getters START ------------------------------------ */
    /**
     * Set handler
     *
     * @param \Maleficarum\Response\Http\Handler\AbstractHandler $handler
     *
     * @return \Maleficarum\Response\Http\Response
     */
    public function setHandler(\Maleficarum\Response\Http\Handler\AbstractHandler $handler) : \Maleficarum\Response\Http\Response {
        $this->handler = $handler;

        return $this;
    }
    /* ------------------------------------ Setters & Getters END -------------------------------------- */
}
