<?php
/**
 * This class provides functionality of handling JSON response
 */

namespace Maleficarum\Response\Http\Handler;

class JsonHandler extends \Maleficarum\Response\Http\Handler\AbstractHandler {
    /* ------------------------------------ AbstractHandler methods START ------------------------------ */
    /**
     * Handle JSON response
     *
     * @see \Maleficarum\Response\Http\Handler\AbstractHandler::handle()
     *
     * @param array $data
     * @param array $meta
     * @param bool $success
     *
     * @return \Maleficarum\Response\Http\Handler\AbstractHandler
     */
    public function handle(array $data = [], array $meta = [], bool $success = true) : \Maleficarum\Response\Http\Handler\AbstractHandler {
        // initialize response content
        $meta = array_merge($meta, [
            'status' => $success ? 'success' : 'failure'
        ]);

        $this->body = [
            'meta' => $meta,
            'data' => $data
        ];

        return $this;
    }

    /**
     * Get response content type
     *
     * @see \Maleficarum\Response\Http\Handler\AbstractHandler::getContentType()
     * @return string
     */
    public function getContentType() : string {
        return 'application/json';
    }
    /* ------------------------------------ AbstractHandler methods END -------------------------------- */

    /* ------------------------------------ Setters & Getters START ------------------------------------ */
    /**
     * Get response body
     *
     * @see \Maleficarum\Response\Http\Handler\AbstractHandler::getBody()
     * @return string
     */
    public function getBody() : string {
        isset($this->body['meta']) or $this->body['meta'] = [];
        foreach ($this->plugins as $name => $plugin) {
            $this->body['meta'][$name] = $plugin();
        }

        return json_encode($this->body);
    }
    /* ------------------------------------ Setters & Getters END -------------------------------------- */

}
