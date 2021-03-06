<?php
class ControllerExtensionModuleCategory extends Controller {
	public function index() {
		$this->load->language('extension/module/category');

		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		if (isset($parts[0])) {
			$data['category_id'] = $parts[0];
		} else {
			$data['category_id'] = 0;
		}

		if (isset($parts[1])) {
			$data['child_id'] = $parts[1];
		} else {
			$data['child_id'] = 0;
		}

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

        /*$data['categories'] = array();

        $categories = $this->model_catalog_category->getCategories(0);

        foreach ($categories as $category) {
            $children_data = array();

            if ($category['category_id'] == $data['category_id']) {
                $children = $this->model_catalog_category->getCategories($category['category_id']);

                foreach($children as $child) {
                    $filter_data = array('filter_category_id' => $child['category_id'], 'filter_sub_category' => true);

                    $children_data[] = array(
                        'category_id' => $child['category_id'],
                        'name' => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
                        'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
                    );
                }
            }

            $filter_data = array(
                'filter_category_id'  => $category['category_id'],
                'filter_sub_category' => true
            );

            $data['categories'][] = array(
                'category_id' => $category['category_id'],
                'name'        => $category['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
                'children'    => $children_data,
                'href'        => $this->url->link('product/category', 'path=' . $category['category_id'])
            );
        }*/

        $allcategories = $this->model_catalog_category->getAllCategories();

        $categories = $this->form_tree($allcategories);
        $data['categories'] = $this->build_tree($categories, 0, "");

		return $this->load->view('extension/module/category', $data);
	}

    private function form_tree($mess)
    {
        if (!is_array($mess)) {
            return false;
        }
        $tree = array();
        foreach ($mess as $value) {
            $tree[$value['parent_id']][] = $value;
        }
        return $tree;
    }

    //$parent_id - какой parentid считать корневым
    //по умолчанию 0 (корень)
    private function build_tree($cats, $parent_id, $pathy)
    {
        if (is_array($cats) && isset($cats[$parent_id])) {
            $tree = array();
            foreach ($cats[$parent_id] as $cat) {
                $tree[] = array(
                    'category_id' => $cat['category_id'],
                    'name'        => $cat['name'],
                    'href'        => $this->url->link('product/category', 'path=' . ($pathy <> "" ? $pathy."_" : "") . $cat['category_id']),
                    'children'    => $this->build_tree($cats, $cat['category_id'], ($pathy <> "" ? $pathy."_" : "").$cat['category_id'])
                );
            }
        } else {
            return false;
        }
        return $tree;
    }

}