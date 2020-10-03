<?php
/**
 * Base Controller
 *
 * @author inlee <einable@gmail.com>
 */

namespace Foundation;

abstract class BaseController
{
    /**
     * Render files with parameters
     *
     * @param string $file          file
     * @param array $params         Render parameters
     * @param boolean $useSanitize  Use sanitize(http minify, remove comments)
     *                              or not. Default is true (use sanitize).
     */
    protected function render($file, $params = array(), $useSanitize = true)
    {
        extract($params);

        ob_start();

        include $file;
        $content = ob_get_contents();

        ob_end_clean();

        return $this->sanitize($content);
    }

    /**
     * Redirect URL
     *
     * @param string $url   Redirect URL
     */
    protected function redirect($url)
    {
        header("Location: {$url}");
        exit();
    }

    /**
     * HTML minify 및 주석 제거
     *
     * @param string $str   HTML string
     * @return string       Sanitized HTML
     * @see https://stackoverflow.com/questions/6225351/how-to-minify-php-page-html-output
     */
    private function sanitize($str)
    {
        $search = array(
            '/\>[^\S]+/s', // strip whitespaces after tags, except space
            '/[^\S]+\</s', // strip whitespaces before tags, except space
            '/(\s)+/s', // shorten multiple whitespace sequences
            '/<!--(.|\s)*?-->/' // Remove HTML comments
        );

        $replace = array('>', '<', '\\1', '');

        $buffer = preg_replace($search, $replace, $str);

        return $buffer;
    }
}
