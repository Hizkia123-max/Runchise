<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table            = 'menus';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['parent_id', 'title', 'url', 'icon', 'is_active', 'order_index', 'roles'];
    protected $useTimestamps    = true;

    /**
     * Get active menu tree for a specific role
     */
    public function getMenuTree($role = null)
    {
        $db = db_connect();
        $builder = $db->table($this->table);
        $builder->where('is_active', 1);
        $builder->orderBy('order_index', 'ASC');
        
        $allMenus = $builder->get()->getResultArray();
        
        // Filter by role if provided
        if ($role) {
            $allMenus = array_filter($allMenus, function($menu) use ($role) {
                if (empty($menu['roles'])) return true;
                $roles = json_decode($menu['roles'], true);
                if (is_array($roles)) {
                    return in_array($role, $roles);
                }
                return true; // fallback
            });
        }

        // Build tree
        $tree = [];
        $children = [];

        foreach ($allMenus as $menu) {
            if ($menu['parent_id']) {
                $children[$menu['parent_id']][] = $menu;
            } else {
                $tree[$menu['id']] = $menu;
                $tree[$menu['id']]['children'] = [];
            }
        }

        foreach ($children as $parentId => $childItems) {
            if (isset($tree[$parentId])) {
                $tree[$parentId]['children'] = $childItems;
            }
        }

        // Remove empty parents (optional, but good if they have no url and no children)
        $tree = array_filter($tree, function($parent) {
            return !empty($parent['url']) || !empty($parent['children']);
        });

        return array_values($tree);
    }
}
