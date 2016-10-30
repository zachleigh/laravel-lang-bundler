<?php

/**
 * Translate the given message.
 *
 * @param  string  $id
 * @param  array   $parameters
 * @param  string  $domain
 * @param  string  $locale
 * @return \Symfony\Component\Translation\TranslatorInterface|string
 */
function transB($id = null, $parameters = [], $domain = 'messages', $locale = null)
{
    if (is_null($id)) {
        return app('LaravelLangBundler\LangBundler');
    }

    return app('LaravelLangBundler\LangBundler')->trans($id, $parameters, $domain, $locale);
}
