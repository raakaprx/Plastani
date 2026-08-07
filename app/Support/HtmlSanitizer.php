<?php

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;
use Illuminate\Support\Str;

class HtmlSanitizer
{
    /**
     * Sanitize article HTML to mitigate stored XSS attacks.
     */
    public function sanitizeArticleContent(string $html): string
    {
        $html = trim($html);

        if ($html === '') {
            return '';
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previousErrorHandling = libxml_use_internal_errors(true);

        // Wrap with a root node so we can safely parse partial HTML.
        $document->loadHTML(
            '<?xml encoding="UTF-8"><div>'.$html.'</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        libxml_clear_errors();
        libxml_use_internal_errors($previousErrorHandling);

        $root = $document->getElementsByTagName('div')->item(0);

        if (! $root instanceof DOMElement) {
            return e($html);
        }

        $this->sanitizeNode($root);

        $sanitized = '';
        for ($i = 0; $i < $root->childNodes->length; $i++) {
            $sanitized .= $document->saveHTML($root->childNodes->item($i));
        }

        return $sanitized;
    }

    private function sanitizeNode(DOMNode $node): void
    {
        for ($i = $node->childNodes->length - 1; $i >= 0; $i--) {
            $child = $node->childNodes->item($i);

            if (! $child instanceof DOMElement) {
                continue;
            }

            $tag = strtolower($child->tagName);

            if (! $this->isAllowedTag($tag)) {
                if (in_array($tag, ['script', 'style', 'iframe', 'object', 'embed'], true)) {
                    $child->parentNode?->removeChild($child);
                    continue;
                }

                // Replace unsupported tags with plain text to remove malicious payloads.
                $textNode = $child->ownerDocument->createTextNode($child->textContent ?? '');
                $child->parentNode?->replaceChild($textNode, $child);
                continue;
            }

            $this->sanitizeAttributes($child, $tag);
            $this->sanitizeNode($child);
        }
    }

    private function sanitizeAttributes(DOMElement $element, string $tag): void
    {
        $allowedAttributes = $this->allowedAttributes()[$tag] ?? [];

        for ($i = $element->attributes->length - 1; $i >= 0; $i--) {
            $attribute = $element->attributes->item($i);

            if (! $attribute) {
                continue;
            }

            $name = strtolower($attribute->nodeName);

            if (Str::startsWith($name, 'on') || ! in_array($name, $allowedAttributes, true)) {
                $element->removeAttribute($attribute->nodeName);
            }
        }

        if ($tag === 'a') {
            $href = trim($element->getAttribute('href'));

            if (! $this->isSafeHref($href)) {
                $element->removeAttribute('href');
            }

            $target = strtolower($element->getAttribute('target'));
            if ($target === '_blank') {
                $element->setAttribute('rel', 'noopener noreferrer nofollow');
            } else {
                $element->removeAttribute('target');
                $element->removeAttribute('rel');
            }
        }
    }

    private function isAllowedTag(string $tag): bool
    {
        return in_array($tag, $this->allowedTags(), true);
    }

    private function isSafeHref(string $href): bool
    {
        if ($href === '') {
            return false;
        }

        if (Str::startsWith($href, ['#', '/'])) {
            return true;
        }

        if (! filter_var($href, FILTER_VALIDATE_URL)) {
            return false;
        }

        return Str::startsWith(strtolower($href), ['http://', 'https://']);
    }

    private function allowedTags(): array
    {
        return [
            'p',
            'br',
            'strong',
            'b',
            'em',
            'i',
            'u',
            'ul',
            'ol',
            'li',
            'h2',
            'h3',
            'h4',
            'blockquote',
            'a',
        ];
    }

    private function allowedAttributes(): array
    {
        return [
            'a' => ['href', 'title', 'target', 'rel'],
        ];
    }
}
