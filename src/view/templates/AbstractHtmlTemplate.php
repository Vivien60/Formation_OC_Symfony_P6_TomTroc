<?php
declare(strict_types=1);

namespace view\templates;

use view\layouts\AbstractLayout;

abstract class AbstractHtmlTemplate
{

    protected AbstractLayout $layout;
    /**
     * @var string[]
     */
    public array $headerHTML;
    public string $title = '';

    public string $footer = '';
    public string $contentHeader = '';
    public static string $baseUrl = '/';

    /**
     * Helper for UI. It will contain some formatted data that can be used in the UI.
     * @var array $helper
     */
    public array $helper = [];

    /**
     * @param AbstractLayout $layout
     * @param string[] $headers
     */
    public function __construct(AbstractLayout $layout, array $headers = [], array $helper = [])
    {
        $this->layout = $layout;
        $this->headerHTML = $headers?:$this->defaultHeaderHtml();
        $this->helper = $helper;
    }

    public function render() : string
    {
        $this->buildComposants();
        return $this->layout->buildPageFromTemplate($this);
    }

    protected function buildComposants(): void
    {
        $this->contentHeader = include dirname(__DIR__) . '/ui/header.php';
        $this->footer = include dirname(__DIR__) . '/ui/footer.php';
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
            "<base href='{$this->getBaseUrl()}'>",
            '<link rel="stylesheet" type="text/css" href="assets/css/importsCSS.css" media="screen">',
            '<script src="assets/js/main.js"></script>',
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

    public function addToHelper(array $array): void
    {
        $this->helper = array_merge($this->helper, $array);
    }

    /**
     * @TOVOERRIDE
     * Build helper for UI. It contains some formatted data that can be used in the UI.
     * @return void
     */
    protected function prepareHelper() : void
    {
    }

    public function helper(string $valName) : mixed
    {
        return $this->helper[$valName];
    }

    /**
     * Escape HTML entities for safe output in views.
     * Protects against XSS (Cross-Site Scripting) attacks.
     *
     * @param string $string The string to escape
     * @return string The escaped string with special characters converted to HTML entities
     */
    public function e(mixed $string): string
    {
        return \lib\Utils::filterInput((string)$string);
    }
}