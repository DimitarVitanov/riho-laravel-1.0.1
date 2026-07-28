<?php

namespace App\Services\Taxi;

use DOMDocument;
use DOMElement;
use DOMXPath;

/**
 * Thin helper around DOMDocument for Real Estate Taxi country reports.
 * Every operation preserves the original markup byte-for-byte except for the
 * nodes that are explicitly replaced.
 */
class TaxiReportHtml
{
    private DOMDocument $dom;

    private function __construct(DOMDocument $dom)
    {
        $this->dom = $dom;
    }

    public static function load(string $html): self
    {
        $dom = new DOMDocument('1.0', 'UTF-8');

        $previous = libxml_use_internal_errors(true);
        $dom->loadHTML(
            '<?xml encoding="UTF-8">' . $html,
            LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING
        );
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        return new self($dom);
    }

    public function dom(): DOMDocument
    {
        return $this->dom;
    }

    public function xpath(): DOMXPath
    {
        return new DOMXPath($this->dom);
    }

    public function title(): ?string
    {
        $node = $this->xpath()->query('//title')->item(0);

        return $node ? trim($node->textContent) : null;
    }

    public function setTitle(string $title): void
    {
        $node = $this->xpath()->query('//title')->item(0);
        if ($node) {
            $node->nodeValue = htmlspecialchars($title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }
    }

    public function metaContent(string $name): ?string
    {
        $node = $this->xpath()->query("//meta[@name='{$name}']")->item(0);

        return $node instanceof DOMElement ? $node->getAttribute('content') : null;
    }

    public function setMetaContent(string $name, string $value): void
    {
        $node = $this->xpath()->query("//meta[@name='{$name}']")->item(0);
        if ($node instanceof DOMElement) {
            $node->setAttribute('content', $value);
        }
    }

    public function canonical(): ?string
    {
        $node = $this->xpath()->query("//link[@rel='canonical']")->item(0);

        return $node instanceof DOMElement ? $node->getAttribute('href') : null;
    }

    public function heading(): ?string
    {
        $node = $this->xpath()->query('//h1')->item(0);

        return $node ? trim($node->textContent) : null;
    }

    /**
     * Top-level report blocks inside <main>: sections and articles that carry
     * visible copy. Returned as [id|generated key => DOMElement].
     *
     * @return array<string, DOMElement>
     */
    public function contentBlocks(): array
    {
        $blocks = [];
        // Leaf blocks only: a wrapper <section> that contains <article> cards is
        // skipped so the same copy is never rewritten twice.
        $nodes = $this->xpath()->query(
            '//main//section[not(.//section) and not(.//article)] | //main//article[not(.//article)]'
        );

        $i = 0;
        foreach ($nodes as $node) {
            if (!$node instanceof DOMElement) {
                continue;
            }

            $text = trim(preg_replace('/\s+/', ' ', $node->textContent));
            if (mb_strlen($text) < 120) {
                continue; // navigation strips, emblems, tiny cards
            }

            $id = $node->getAttribute('id') ?: 'block-' . (++$i);
            $blocks[$id] = $node;
        }

        return $blocks;
    }

    public function innerHtml(DOMElement $element): string
    {
        $html = '';
        foreach ($element->childNodes as $child) {
            $html .= $this->dom->saveHTML($child);
        }

        return $html;
    }

    public function replaceInnerHtml(DOMElement $element, string $html): bool
    {
        $fragment = $this->dom->createDocumentFragment();

        $previous = libxml_use_internal_errors(true);
        $ok = @$fragment->appendXML($this->toXmlSafe($html));
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (!$ok || !$fragment->hasChildNodes()) {
            return false;
        }

        while ($element->firstChild) {
            $element->removeChild($element->firstChild);
        }
        $element->appendChild($fragment);

        return true;
    }

    /**
     * appendXML() needs well-formed XML; close the void elements that HTML allows.
     */
    private function toXmlSafe(string $html): string
    {
        $void = ['br', 'hr', 'img', 'input', 'meta', 'link', 'source'];
        foreach ($void as $tag) {
            $html = preg_replace('/<' . $tag . '([^>]*?)(?<!\/)>/i', '<' . $tag . '$1/>', $html);
        }

        return '<wrapper>' . $html . '</wrapper>';
    }

    /**
     * All translatable text nodes (skips script/style and empty whitespace).
     *
     * @return array<int, \DOMText>
     */
    public function textNodes(): array
    {
        $result = [];
        foreach ($this->xpath()->query('//body//text()') as $node) {
            $parent = $node->parentNode?->nodeName;
            if (in_array($parent, ['script', 'style'], true)) {
                continue;
            }
            if (trim($node->nodeValue) === '') {
                continue;
            }
            $result[] = $node;
        }

        return $result;
    }

    public function save(): string
    {
        $html = $this->dom->saveHTML();
        $html = preg_replace('/^<\?xml encoding="UTF-8"\?>\s*/', '', (string) $html);

        return trim((string) $html);
    }
}
