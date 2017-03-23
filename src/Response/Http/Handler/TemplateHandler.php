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
     * @var \Phalcon\Mvc\View\Engine\Volt
     */
    private $view;

    /* ------------------------------------ Class Property END ----------------------------------------- */

    /* ------------------------------------ Magic methods START ---------------------------------------- */
    
    /**
     * TemplateHandler constructor.
     *
     * @param \Phalcon\Mvc\View\Engine\Volt $view
     */
    public function __construct(\Phalcon\Mvc\View\Engine\Volt $view) {
        $this->view = $view;
    }
    
    /* ------------------------------------ Magic methods END ------------------------------------------ */

    /* ------------------------------------ Class Methods START ---------------------------------------- */
    
    /**
     * @see \Maleficarum\Response\Http\Handler\AbstractHandler::handle()
     */
    public function handle(string $template = '', array $data = []) : \Maleficarum\Response\Http\Handler\AbstractHandler {
        if (empty($template)) {
            throw new \InvalidArgumentException(sprintf('Invalid template path provided. \%s::handle()', static::class));
        }

        $template = $this->view->getView()->getViewsDir() . $template . '.phtml';

        // initialize response content
        $this->body = $this->view->render($template, $data);

        return $this;
    }

    /**
     * @see \Maleficarum\Response\Http\Handler\AbstractHandler::getContentType()
     */
    public function getContentType() : string {
        return 'text/html';
    }
    
    /* ------------------------------------ AbstractHandler methods END -------------------------------- */
}
