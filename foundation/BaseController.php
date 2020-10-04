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

        return $this->sanitizeView($content);
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
    private function sanitizeView($str)
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

    /**
     * XSS 공격 방지를 위해 sanitize 한다.
     *
     * @param string $str   Sanitize대상 string
     * @return string       XSS Filtered string
     */
    protected function sanitizeStr($str)
    {
        return htmlspecialchars(strip_tags($str));
    }

    /**
     * XSS 공격 방지를 위해 배열 요소들을 Sanitize한 결과를 반환한다.
     *
     * @param array $array  Sanitize 대상이 저장된 array
     * @return array        XSS Filtered array
     */
    protected function sanitizeRequest($array)
    {
        $ret = array_walk_recursive($array, function (&$input) {
            $input = htmlspecialchars(strip_tags($input));
        });

        return $array;
    }
}
