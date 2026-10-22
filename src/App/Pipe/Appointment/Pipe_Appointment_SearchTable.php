<?php

class Pipe_Appointment_SearchTable {
    private $app;
    private $session;
    private $asset;
    private $appointmentRepo;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,

            $this->appointmentRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;
        $data = $input->query;
        
        $user = $this->session->get('user');
    
        $status = isset($data['status']) ? trim($data['status']) : '';
        $keyword = isset($data['search']) ? trim($data['search']) : '';
        $fromDate = isset($data['from-date']) ? trim($data['from-date']) : '';
        $toDate = isset($data['to-date']) ? trim($data['to-date']) : '';
    
        // Base query
        $sql = "
            SELECT
                cs.date,
                a.id,
                a.schedule_id,
                a.time_start,
                a.time_end,
                a.status,
                a.user_id,
                a.service_id,
                s.name AS service_name,
                s.description AS service_description,
                s.duration AS service_duration,
                s.price AS service_price,
                cs.date AS schedule_date,
                u.id AS user_id,
                u.firstname,
                u.middlename,
                u.lastname,
                u.email,
                d.id AS doctor_id,
                d.firstname AS doctor_firstname,
                d.middlename AS doctor_middlename,
                d.lastname AS doctor_lastname,
                d.email AS doctor_email
            FROM
                appointment a
            LEFT JOIN
                schedule cs ON cs.id = a.schedule_id
            LEFT JOIN
                service s ON s.id = a.service_id
            LEFT JOIN
                user u ON u.id = a.user_id
            LEFT JOIN
                user d ON d.id = cs.user_id
        ";

        $view = '';

        if ($user['role'] == 'doctor') {
            $conditions = ['a.type = ?', 'cs.user_id = ?'];
            $param = ['regular', $user['id']];
            $view = 'res/app/view/doctor/appointment/search-table.ajax.html.php';
        } else if ($user['role'] == 'patient') {
            $conditions = ['a.user_id = ?'];
            $param = [$user['id']];
            $view = 'res/app/view/appointment/search-table.ajax.html.php';
        } else if ($user['role'] == 'staff') {
            $conditions = ['a.type = ?'];
            $param = ['regular'];
            $view = 'res/app/view/staff/appointment/search-table.ajax.html.php';
        }

        // Filter by status if provided
        if ($status !== '') {
            $conditions[] = "a.status = ?";
            $param[] = $status;
        }
    
        // Filter by keyword if provided (on a.id and s.name)
        if ($keyword !== '') {
            $conditions[] = "(CAST(a.id AS CHAR) LIKE ? OR u.firstname LIKE ? OR u.middlename LIKE ? OR u.lastname LIKE ? OR u.email LIKE ? OR d.firstname LIKE ? OR d.middlename LIKE ? OR d.lastname LIKE ? OR d.email LIKE ?)";
            $likeKeyword = '%' . $keyword . '%';
            $param[] = $likeKeyword;
            $param[] = $likeKeyword;
            $param[] = $likeKeyword;
            $param[] = $likeKeyword;
            $param[] = $likeKeyword;
            $param[] = $likeKeyword;
            $param[] = $likeKeyword;
            $param[] = $likeKeyword;
            $param[] = $likeKeyword;
        }


        if ($fromDate !== '') {
            $conditions[] = "cs.date >= ?";
            $param[] = $fromDate;
        }
        if ($toDate !== '') {
            $conditions[] = "cs.date <= ?";
            $param[] = $toDate;
        }

    
        // Add WHERE clause if conditions exist
        if (count($conditions) > 0) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
    
        // Add order by
        $sql .= " ORDER BY cs.date, cs.time_start";
    
        $appointments = $this->appointmentRepo->query($sql, $param);
    
        $output->content = $this->app->template($this->app->dirRoot($view), array(
            'app' => $this->app,
            'output' => $output,
            'flash' => $this->session->unset('flash'),
            'csrf_token' => $this->session->get('csrf_token'),
            'current_route' => $input->route,
            'appointments' => $appointments,
        ));
    
        return array($input, $output, $success);
    }

}