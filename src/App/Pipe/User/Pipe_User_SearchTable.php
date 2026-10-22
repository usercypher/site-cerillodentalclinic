<?php

class Pipe_User_SearchTable {
    private $app;
    private $session;
    private $userRepo;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,
            $this->userRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;
        $data = $input->query;
        
        $user = $this->session->get('user');
    
        $role = isset($data['role']) ? trim($data['role']) : '';
        $keyword = isset($data['search']) ? trim($data['search']) : '';

        // Base query
        $sql = "";

        $conditions = [];
        $param = [];
        $view = 'res/app/view/admin/user/search-table.ajax.html.php';


        // Filter by role if provided
        if ($role !== '') {
            $conditions[] = "role = ?";
            $param[] = $role;
        }
    
        // Filter by keyword if provided (on a.id and s.name)
        if ($keyword !== '') {
            $conditions[] = "(CAST(id AS CHAR) LIKE ? OR firstname LIKE ? OR middlename LIKE ? OR lastname LIKE ? OR email LIKE ?)";
            $likeKeyword = '%' . $keyword . '%';
            $param[] = $likeKeyword;
            $param[] = $likeKeyword;
            $param[] = $likeKeyword;
            $param[] = $likeKeyword;
            $param[] = $likeKeyword;
        }
    
        // Add WHERE clause if conditions exist
        if (count($conditions) > 0) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
    
        // Add order by
        $sql .= " ORDER BY lastname";
    
        $users = $this->userRepo->all($sql, $param);
    
        $output->content = $this->app->template($this->app->dirRoot($view), array(
            'app' => $this->app,
            'output' => $output,
            'flash' => $this->session->unset('flash'),
            'csrf_token' => $this->session->get('csrf_token'),
            'current_route' => $input->route,
            'users' => $users,
        ));
    
        return array($input, $output, $success);
    }

}