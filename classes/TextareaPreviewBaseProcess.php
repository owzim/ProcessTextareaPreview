<?php

/**
 * TextareaPreview Base Process
 *
 * @author Christian Raunitschka (owzim)
 * @copyright Christian Raunitschka
 * <http://ch.rauni.me>
 *
 */

class TextareaPreviewBaseProcess extends Process {

    /**
     * get the path of the module
     * @return string
     */
    protected function getPath() {
        return $this->config->paths->get(get_class($this));
    }

    /**
     * get the url of the module
     * @return string
     */
    protected function getUrl() {
        return $this->config->urls->get(get_class($this));
    }



    /**
     * add an item to the breadcrumbs path, linking to the root page of the module
     * @param string $url
     * @param string $title
     */
    protected function addBreadcrumb($url = null, $title = null) {
        if (!$title) $title = $this->title;
        if (!$url) $url = $this->url;
        $this->breadcrumbs->add(new Breadcrumb($url, $title));
    }

    /**
     * get a the TemplateFile instance of a given template name,
     * which has to reside in the module's templates/ directory
     * @param  string $name the name of the template file without .php extension
     * @return TemplateFile
     */
    protected function getTemplate($name) {
        $suffix = 'templates/' . $name . '.php';
        $file = $this->getPath() . $suffix;
        if (!file_exists($file)) {
            $this->error("TextareaPreview::getTemplate: '{$suffix}' does not exist.");
        }
        return new TemplateFile($file);
    }

    /**
     * get the url of the given page name
     * @param  string $pageName
     * @return string
     */
    protected function getPageUrl($pageName = null) {
        if (!$pageName) $pageName = $this->pageName;
        return $this->pages->get('template=admin, name=' . $pageName)->url;
    }

    /**
     * add a JS file to config's styles array,
     * which has to reside in the module's scripts/ directory
     * @param string $name the name of the file without .js extension
     * @return string the url of the added script
     */
    protected function addScript($name) {
        $url = $this->getScriptUrl($name);
        $this->config->scripts->add($url);
        return $url;
    }


    /**
     * add a CSS file to config's styles array,
     * which has to reside in the module's styles/ directory
     * @param string $name the name of the file without .css extension
     * @return string the url of the added style
     */
    protected function getScriptUrl($name) {
        $suffix = 'scripts/' . $name . '.js';

        $url = $this->getUrl() . $suffix;
        $path = $this->getPath() . $suffix;

        if (!file_exists($path)) {
            $this->error("TextareaPreview::addScript: '{$suffix}' does not exist.");
        }

        return $url;
    }

    /**
     * add a CSS file to config's styles array,
     * which has to reside in the module's styles/ directory
     * @param string $name the name of the file without .css extension
     * @return string the url of the added style
     */
    protected function addStyle($name) {
        $url = $this->getStyleUrl($name);
        $this->config->styles->add($url);
        return $url;
    }


    /**
     * add a CSS file to config's styles array,
     * which has to reside in the module's styles/ directory
     * @param string $name the name of the file without .css extension
     * @return string the url of the added style
     */
    protected function getStyleUrl($name) {
        $suffix = 'styles/' . $name . '.css';

        $url = $this->getUrl() . $suffix;
        $path = $this->getPath() . $suffix;

        if (!file_exists($path)) {
            $this->error("TextareaPreview::getStyleUrl: '{$suffix}' does not exist.");
        }

        return $url;
    }

    /**
     * set the headline of the current item in breadcrums path
     * @param string $headline
     */
    protected function setHeadline($headline) {
        $this->wire('processHeadline', $headline);
    }

    /**
     * install page
     * @param  Page $parent
     * @param  string $pageName
     * @param  string $title
     * @param  string $template
     * @return Page
     */
    protected function installPage($parent, $pageName = null, $title = null, $template = 'admin') {
        if (!$title) $title = $this->title;
        if (!$pageName) $pageName = $this->pageName;

        $moduleID = $this->modules->getModuleID($this);
        $page = $this->pages->get("template=admin, process={$moduleID}, name={$this->pageName}, include=all");
        if (!$page->id || $parent->id !== $page->parent->id) {
            $page = new Page();
            $page->template = $template;
            $page->name = $pageName;
            $page->parent = $parent;
            $page->process = $this;
            $page->title = $title;
            $page->save();
            $this->message("Created {$page->title} page: {$page->path}");
        }

        return $page;
    }

    /**
     * install permission
     * @param  string $name
     * @param  string $title
     * @return Page
     */
    protected function installPermission($name, $title) {
        $permission = $this->permissions->get($name);
        if (!$permission->id) {
            $permission = new Permission();
            $permission->name = $name;
            $permission->title = $this->_($title);
            $permission->save();
            $this->message("Created {$permission->title} permission: {$permission->name}");
        }

        return $permission;
    }

    /**
     * uninstall permission
     * @param  string $name
     * @param  string $title
     * @return Permission
     */
    protected function uninstallPermission($name, $title) {
        $permission = $this->permissions->get($name);
        if ($permission->id) {
            $permission->delete();
            $this->message("Deleted {$title} permission: {$name}");
        }

        return $permission;
    }

    /**
     * uninstall all pages with the module's id as process
     * @return void
     */
    protected function uninstallPages() {
        $moduleID = $this->modules->getModuleID($this);
        $pagesWithModuleID = $this->pages->find("template=admin, process={$moduleID}, include=all");
        foreach ($pagesWithModuleID as $page) {
            $title = $page->title;
            $page->delete();
            $this->message("Deleted {$title} page: {$page->path}");
        }
    }
}
