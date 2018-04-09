<?php
/**
 * @package   Kholifa CMS
 */

namespace including;

/**
 * Page content block handling
 *
 * @package including
 */

class Block
{
    private $exampleContent = '';
    private $name;
    private $isStatic = false;

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Render page block content
     * @param int $revisionId
     * @return string
     */
    public function render($revisionId = 0)
    {
        $data = array(
            'blockName' => $this->name,
        );

        $content = knJob('knGenerateBlock', $data);

        if ($content) {
            if (is_object($content) && method_exists($content, 'render')) {
                $content = $content->render();
            }
            return (string)$content;
        } else {
            $content = $this->generateBlockHtml($revisionId);
        }
        return knFilter('knBlockContent', $content, $data);
    }

    private function generateBlockHtml($revisionId)
    {
        $predefinedContent = \including\ServiceLocator::content()->getBlockContent($this->name);
        if ($predefinedContent !== null) {
            return $predefinedContent;
        }

        if (knContent()->getCurrentPage() == null && $revisionId == 0 && !$this->isStatic) {
            return '';
        }

        if ($this->isStatic) {
            $languageId = knContent()->getCurrentLanguage()->getId();
            $revisionId = 0;
        } else {
            if ($revisionId === 0) {
                $revision = \including\ServiceLocator::content()->getCurrentRevision();
                if ($revision) {
                    $revisionId = $revision['revisionId'];
                }
            }

            $languageId = 0;
        }

        return \including\Internal\Content\Model::generateBlock(
            $this->name,
            $revisionId,
            $languageId,
            knIsManagementState(),
            $this->exampleContent
        );
    }

    /**
     * Make a block content the same on all pages
     *
     * @return $this
     */
    public function asStatic()
    {
        $this->isStatic = true;
        return $this;
    }

    /**
     * Set example content to be used when block content is empty
     *
     * @param string $content
     * @return $this
     */
    public function exampleContent($content)
    {
        $this->exampleContent = $content;
        return $this;
    }

    /**
     * Load example content from file
     *
     * Example content should be placed in theme's directory
     *
     * @param string $filename
     * @return $this
     */
    public function exampleContentFrom($filename)
    {
        $this->exampleContent = knView(knThemeFile($filename));
        return $this;
    }

    /**
     * PHP can't handle exceptions in __toString method. Try to avoid it every time possible. Use render() method instead.
     * @ignore
     * @return string
     */
    public function __toString()
    {
        try {
            $content = $this->render();
        } catch (\Exception $e) {
            /*
            __toString method can't throw exceptions. In case of exception you will end with unclear error message.
            We can't avoid that here. So just logging clear error message in logs and rethrowing the same exception.
            */
            knLog()->error(
                'Block.toStringException: Exception in block `{block}` __toString() method.',
                array('block' => $this->name, 'exception' => $e)
            );
            return $e->getTraceAsString();
        }
        return $content;
    }

}
