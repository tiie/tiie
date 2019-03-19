<?php
namespace Tiie\Data\Model;

use Tiie\Data\Model\ModelInterface;

class Pagination
{
    private $model;
    private $params = array();
    private $search = array();
    private $page = 0;
    private $size = 10;
    private $link;

    function __construct(ModelInterface $model, array $params = array())
    {
        $this->model = $model;

        if (array_key_exists('page', $params)) {
            $this->page = $params['page'];
        }

        if (array_key_exists('size', $params)) {
            $this->size = $params['size'];
        }
    }

    public function link(string $link) : Pagination
    {
        $this->link = $link;

        return $this;
    }

    public function page(string $page) : Pagination
    {
        $this->page = $page;

        return $this;
    }

    public function size(string $size) : Pagination
    {
        $this->size = $size;

        return $this;
    }

    public function params(array $params = array(), int $merge = 1) : Pagination
    {
        $this->params = $params;

        if ($merge) {
            $this->params = array_merge($this->params, $params);
        } else {
            $this->params = $params;
        }

        return $this;
    }

    public function search(array $search = array(), int $merge = 1) : Pagination
    {
        $this->search = $search;

        if ($merge) {
            $this->search = array_merge($this->search, $search);
        } else {
            $this->search = $search;
        }

        return $this;
    }

    public function generate()
    {
        $pagination = array();

        $number = $this->model->count($this->search);
        $pagination['total'] = $number;

        if (!is_null($this->size)) {
            $pagination['size'] = $this->size;
            $pagination['page'] = $this->page + 1;
            $pagination['offset'] = $this->page * $this->size;

            // Pages
            $pages = ceil($number / $this->size);
            $pagination['pages'] = $pages;

            if ($pages > 1) {
                $pagination['pagination'] = 1;
            } else {
                $pagination['pagination'] = 0;
            }

            if ($pages > 1) {
                if ($this->page > 0) {
                    $params = $this->params;
                    $params['page'] = $this->page - 1;

                    $pagination['previous'] = sprintf("%s?%s", $this->link, http_build_query($params));

                    // First page.
                    $params['page'] = 0;

                    $pagination['first'] = sprintf("%s?%s", $this->link, http_build_query($params));
                } else {
                    $pagination['previous'] = null;
                    $pagination['first'] = null;
                }

                if ($this->page < ($pages - 1)) {
                    $params = $this->params;
                    $params['page'] = $this->page + 1;

                    $pagination['next'] = sprintf("%s?%s", $this->link, http_build_query($params));

                    // Last page.
                    $params['page'] = $pages - 1;

                    $pagination['last'] = sprintf("%s?%s", $this->link, http_build_query($params));
                } else {
                    $pagination['next'] = null;
                    $pagination['last'] = null;
                }
            }
        }

        return $pagination;
    }
}
