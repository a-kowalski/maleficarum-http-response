<?php
/**
 * This class provides functionality of handling JSON response.
 */
declare (strict_types=1);

namespace Maleficarum\Response\Http\Handler;

class JsonHandler extends \Maleficarum\Response\Http\Handler\AbstractHandler {
    /* ------------------------------------ Class Methods START ---------------------------------------- */

    /**
     * @see \Maleficarum\Response\Http\Handler\AbstractHandler::handle()
     */
    public function handle(array $data = [], array $meta = [], bool $success = true): \Maleficarum\Response\Http\Handler\AbstractHandler {
        // initialize response content
        $meta = array_merge($meta, [
            'status' => $success ? 'success' : 'failure',
        ]);

        $this->body = [
            'meta' => $meta,
            'data' => $data,
        ];

        return $this;
    }

    /**
     * @see \Maleficarum\Response\Http\Handler\AbstractHandler::getContentType()
     */
    public function getContentType(): string {
        return 'application/json';
    }

    /* ------------------------------------ Class Methods END ------------------------------------------ */

    /* ------------------------------------ Setters & Getters START ------------------------------------ */

    /**
     * Get response body
     *
     * @see \Maleficarum\Response\Http\Handler\AbstractHandler::getBody()
     * @return string
     */
    public function getBody(): ?string {
        isset($this->body['meta']) or $this->body['meta'] = [];
        foreach ($this->plugins as $plugin) {
            $this->body['meta'][$plugin->getName()] = $plugin->execute();
        }

        return json_encode($this->body);
    }

    /* ------------------------------------ Setters & Getters END -------------------------------------- */
}
