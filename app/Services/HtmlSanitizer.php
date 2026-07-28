<?php

namespace App\Services;

use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Facades\File;

/**
 * Sanitizes rich-text HTML (from the WYSIWYG editor) before it is stored and
 * later rendered with v-html on the storefront. Strips scripts, event handlers
 * and unsafe URL schemes while keeping the basic formatting Quill produces.
 */
class HtmlSanitizer
{
    protected HTMLPurifier $purifier;

    public function __construct()
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('Core.Encoding', 'UTF-8');
        $config->set('HTML.Allowed', 'p,br,strong,b,em,i,u,s,ol,ul,li,a[href|title|target|rel],blockquote,pre,code,h1,h2,h3,span,img[src|alt|width|height]');
        $config->set('HTML.TargetBlank', true);
        $config->set('AutoFormat.RemoveEmpty', true);

        $cacheDir = storage_path('app/htmlpurifier');
        File::ensureDirectoryExists($cacheDir);
        $config->set('Cache.SerializerPath', $cacheDir);

        $this->purifier = new HTMLPurifier($config);
    }

    public function clean(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        return $this->purifier->purify($html);
    }
}
