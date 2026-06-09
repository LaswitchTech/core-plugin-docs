<?php

/**
 * Core Framework - DocsEndpoint
 *
 * @license    MIT (https://mit-license.org/)
 * @author     Louis Ouellet <louis@laswitchtech.com>
 */

// Import additional class into the global namespace
use \LaswitchTech\Core\Abstracts\Endpoint;

class DocsEndpoint extends Endpoint {

    /**
     * Constructor
     */
    public function __construct()
    {
        // Call Parent Constructor
        parent::__construct();

        // Retrieve the namespace
        $namespace = $this->Request->getNamespace();

        // Set Global access
        $this->Public = true;

        // Set Properties
        switch($namespace){
            case "/docs":
                $this->Level = 0;
                break;
            case "/docs/view":
                $this->Level = 0;
                break;
        }
    }

    /**
     * Index action for documentation page
     */
    public function indexAction()
    {
        // Import Global Variables
        global $AUTH, $VIEW, $REQUEST;

        // Prepare data for template
        $data = [
            'title' => 'Documentation',
            'toc' => $this->getTOC(),
            'docs_path' => '/docs'
        ];

        // Render the documentation index page
        $VIEW->render('docs/index', $data);
    }

    /**
     * View action for documentation page
     */
    public function viewAction()
    {
        // Import Global Variables
        global $AUTH, $VIEW, $REQUEST;

        // Get parameters
        $path = $REQUEST->getParams('GET', 'path');
        $file = $REQUEST->getParams('GET', 'file');

        // Check if parameters are provided
        if (empty($path) && empty($file)) {
            // Redirect to main docs page
            header('Location: /docs');
            exit;
        }

        // Build full file path
        $fullPath = '';
        if ($path) {
            $fullPath = $this->Config->root() . DIRECTORY_SEPARATOR . ltrim($path, '/');
        }
        if ($file) {
            $fullPath = $fullPath . DIRECTORY_SEPARATOR . $file;
        }

        // Security check - ensure we're within allowed directories
        $allowed_paths = [
            $this->Config->root() . DIRECTORY_SEPARATOR . 'docs',
            $this->Config->root() . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'plugins',
            $this->Config->root() . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'themes',
            $this->Config->root() . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'modules'
        ];

        $valid_path = false;
        foreach ($allowed_paths as $allowed_path) {
            if (strpos($fullPath, $allowed_path) === 0) {
                $valid_path = true;
                break;
            }
        }

        if (!$valid_path || !file_exists($fullPath)) {
            // Show not found page
            $data = [
                'title' => 'Documentation - Not Found',
                'message' => 'Documentation file not found.'
            ];
            $VIEW->render('docs/notfound', $data);
            return;
        }

        // Read file content and convert markdown to HTML
        $content = file_get_contents($fullPath);
        $html_content = $this->markdownToHtml($content);

        // Get siblings for navigation
        $siblings = $this->getSiblings($path);
        
        // Check if user can edit (Administrator role only)
        $can_edit = $AUTH->isAuthorized('Administrator', 1);

        // Prepare data for template
        $data = [
            'title' => 'Documentation - ' . basename($fullPath),
            'content' => $html_content,
            'file_path' => $path,
            'file_name' => $file,
            'can_edit' => $can_edit,
            'siblings' => $siblings
        ];

        // Render the documentation view page
        $VIEW->render('docs/view', $data);
    }

    /**
     * Get Table of Contents for documentation
     */
    protected function getTOC(): array
    {
        $toc = [];
        
        // Define search paths in order of priority
        $paths = [
            $this->Config->root() . DIRECTORY_SEPARATOR . 'docs',
            $this->Config->root() . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'plugins',
            $this->Config->root() . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'themes',
            $this->Config->root() . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'modules'
        ];

        foreach ($paths as $path) {
            if (is_dir($path)) {
                $toc = array_merge_recursive($toc, $this->scanDirectory($path));
            }
        }

        return $toc;
    }

    /**
     * Recursively scan directory for documentation files
     */
    protected function scanDirectory(string $dir, string $base_path = ''): array
    {
        $toc = [];
        $iterator = new DirectoryIterator($dir);

        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isDot()) continue;
            
            $relative_path = empty($base_path) ? $fileInfo->getFilename() : $base_path . '/' . $fileInfo->getFilename();
            
            if ($fileInfo->isDir()) {
                // For directories, check for docs subdirectory
                $docs_dir = $fileInfo->getPathname() . DIRECTORY_SEPARATOR . 'docs';
                if (is_dir($docs_dir)) {
                    $dir_toc = $this->scanDirectory($docs_dir, $relative_path . '/docs');
                    if (!empty($dir_toc)) {
                        $toc[] = [
                            'name' => $fileInfo->getFilename(),
                            'path' => $relative_path,
                            'type' => 'dir',
                            'items' => $dir_toc
                        ];
                    }
                } else {
                    // If no docs directory, recursively scan for markdown files
                    $toc = array_merge_recursive($toc, $this->scanDirectory($fileInfo->getRealPath(), $relative_path));
                }
            } elseif (strtolower($fileInfo->getExtension()) === 'md' && strpos($fileInfo->getFilename(), 'README') !== 0) {
                // Add markdown file to TOC
                $toc[] = [
                    'name' => basename($fileInfo->getFilename(), '.md'),
                    'path' => $relative_path,
                    'type' => 'file',
                    'filename' => $fileInfo->getFilename()
                ];
            }
        }

        return $toc;
    }

    /**
     * Convert markdown content to HTML
     */
    protected function markdownToHtml(string $markdown): string
    {
        // Simple markdown parser (basic implementation)
        // For production, it would be better to use a proper library like Parsedown
        
        // Convert headers
        $html = preg_replace('/^# (.*)$/m', '<h1>$1</h1>', $markdown);
        $html = preg_replace('/^## (.*)$/m', '<h2>$1</h2>', $html);
        $html = preg_replace('/^### (.*)$/m', '<h3>$1</h3>', $html);
        
        // Convert bold text
        $html = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $html);
        
        // Convert italic text
        $html = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $html);
        
        // Convert code blocks
        $html = preg_replace('/```([\s\S]*?)```/', '<pre><code>$1</code></pre>', $html);
        
        // Convert inline code
        $html = preg_replace('/`(.*?)`/', '<code>$1</code>', $html);
        
        // Convert lists
        $html = preg_replace('/^- (.*?)(\n|$)/', '<li>$1</li>', $html);
        $html = preg_replace('/<li>(.*?)<\/li>/s', '<ul>\n<li>$1</li>\n</ul>', $html);
        
        // Convert links
        $html = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2">$1</a>', $html);
        
        // Convert images
        $html = preg_replace('/!\[([^\]]*)\]\(([^)]+)\)/', '<img src="$2" alt="$1">', $html);
        
        // Convert paragraphs
        $html = preg_replace('/\n\s*\n/', '</p><p>', $html);
        $html = str_replace('<p>', '<p>', trim($html));
        
        return $html;
    }

    /**
     * Get siblings for navigation
     */
    protected function getSiblings(string $path): array
    {
        $siblings = [];
        
        // Simple implementation - in a real system, we would be more sophisticated
        return $siblings;
    }
}