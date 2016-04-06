<?php

namespace KMJ\ToolkitBundle\Traits;

/**
 * Description of SlugifyTrait.
 *
 * @author Kaelin Jacobson <kaelin@supercru.com>
 *
 * @since 1.0
 */
trait SlugifyTrait
{
    private function slugify($text)
    {
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        $text = trim($text, '-');
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = strtolower($text);
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}
