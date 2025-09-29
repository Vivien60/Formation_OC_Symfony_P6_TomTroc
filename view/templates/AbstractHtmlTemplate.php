<?php
declare(strict_types=1);

namespace view\templates;

use view\layouts\Layout;

abstract class AbstractHtmlTemplate
{
    protected Layout $layout;
    /**
     * @var string[]
     */
    public array $headerHTML;
    public string $title = '';

    public string $footer = <<<FOOT
        <span><a href="/rgpd.php">Politique de confidentialité</a></span>
    FOOT;
    public string $contentHeader = <<<CONTENT
        %s
    CONTENT;

    public static string $baseUrl = '/';

    /**
     * @param Layout $layout
     * @param string[] $headers
     */
    public function __construct(Layout $layout, array $headers = [])
    {
        $this->layout = $layout;
        $this->headerHTML = $headers?:$this->defaultHeaderHtml();
        $this->contentHeader = sprintf($this->contentHeader, $this->title);
    }

    public function render() : string
    {
        return $this->layout->buildPageFromTemplate($this);
    }

    abstract public function getMainContent() : string;

    public function getFooter() : string
    {
        return $this->footer;
    }

    /**
     * @return string
     */
    public function getContentHeader(): string
    {
        return $this->contentHeader;
    }

    public function getHeaderHtml() : string
    {
        return implode(PHP_EOL, $this->headerHTML);
    }

    /**
     * @return string[]
     */
    protected function defaultHeaderHtml() : array
    {
        return [
            '<meta charset="UTF-8">',
            '<meta name="viewport" content="width=device-width, initial-scale=1.0">',
            '<title>'.$this->title.'</title>',
            '<link rel="stylesheet" type="text/css" href="css/general.css" media="screen">',
            "<base href='{$this->getBaseUrl()}'>",
        ];
    }

    /**
     * @param string[] $headerHTML
     * @return $this
     */
    public function setHeaderHTML(array $headerHTML) : self
    {
        $this->headerHTML = $headerHTML;
        return $this;
    }

    public function getBaseUrl(): string
    {
        return static::$baseUrl;
    }

    public static function setBaseUrl(string $baseUrl): void
    {
        static::$baseUrl = $baseUrl;
    }
}