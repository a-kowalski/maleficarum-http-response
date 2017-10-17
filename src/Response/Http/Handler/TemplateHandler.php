<?php
/**
 * This class provides functionality of template rendering
 */
declare (strict_types=1);

namespace Maleficarum\Response\Http\Handler;

class TemplateHandler extends \Maleficarum\Response\Http\Handler\AbstractHandler {
    /* ------------------------------------ Class Property START --------------------------------------- */

    /**
     * Internal storage for view engine object
     *
     * @var \Twig_Environment
     */
    private $view;

    /* ------------------------------------ Class Property END ----------------------------------------- */

    /* ------------------------------------ Magic methods START ---------------------------------------- */

    /**
     * TemplateHandler constructor.
     *
     * @param \Twig_Environment $view
     */
    public function __construct(\Twig_Environment $view) {
        $this->view = $view;
    }

    /* ------------------------------------ Magic methods END ------------------------------------------ */

    /* ------------------------------------ Class Methods START ---------------------------------------- */

    /**
     * @see \Maleficarum\Response\Http\Handler\AbstractHandler::handle()
     */
    public function handle(string $template = '', array $data = []): \Maleficarum\Response\Http\Handler\AbstractHandler {
        if (empty($template)) {
            throw new \InvalidArgumentException(sprintf('Invalid template path provided. \%s::handle()', static::class));
        }

        foreach ($this->plugins as $plugin) {
            $data['meta'][$plugin->getName()] = $plugin->execute();
        }

        // initialize response content
        $this->body = $this->view->render($template . '.html', $data);

        return $this;
    }

    /**
     * @see \Maleficarum\Response\Http\Handler\AbstractHandler::getContentType()
     */
    public function getContentType(): string {
        return 'text/html';
    }

    /* ------------------------------------ AbstractHandler methods END -------------------------------- */
}
