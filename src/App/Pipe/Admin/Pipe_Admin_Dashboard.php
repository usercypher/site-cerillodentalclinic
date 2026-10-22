<?php

class Pipe_Admin_Dashboard {
    private $app;
    private $session;
    private $appointmentRepo;
    private $messageRepo;
    private $scheduleRepo;

    public function args($args) {
        // add dependency-only class
        list(
            $this->app,
            $this->session,
            $this->appointmentRepo,
            $this->messageRepo,
            $this->scheduleRepo,
        ) = $args;
    }

    public function process($input, $output) {
        $success = true;
        $filter = $input->getFrom($input->query, 'filter', 'monthly');
        $user = $this->session->get('user');
        $userId = $user['id'];
    
        $apptByStatus = array();
        $dates = array();
        $apptPerDay = array();
        $services = array();
        $serviceTotals = array();
    
        $dateFilter = '';
        if ($filter == 'weekly') {
            $dateFilter = ' AND sch.date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)';
        } elseif ($filter == 'monthly') {
            $dateFilter = ' AND sch.date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)';
        } elseif ($filter == 'yearly') {
            $dateFilter = ' AND sch.date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)';
        }
    
        foreach ($this->appointmentRepo->query('SELECT status, COUNT(*) as total FROM appointment a JOIN schedule sch ON a.schedule_id = sch.id WHERE a.type = ? ' . $dateFilter . ' GROUP BY status', array('regular')) as $a) {
            $apptByStatus[$a['status']] = (int)$a['total'];
        }
    
        foreach ($this->appointmentRepo->query(" 
            SELECT sch.date, a.status, COUNT(a.id) AS total 
            FROM appointment a 
            JOIN schedule sch ON a.schedule_id = sch.id 
            WHERE a.type = ? " . $dateFilter . " 
            GROUP BY sch.date, a.status 
            ORDER BY sch.date 
        ", array('regular')) as $a) {
            $dates[] = $a['date'];
            $status = $a['status'];
            $apptPerDay[$a['date']][$status] = (int)$a['total'];
        }
    
        foreach ($this->appointmentRepo->query("
            SELECT 
                s.name, 
                COUNT(a.id) AS total 
            FROM appointment a 
            JOIN schedule sch ON a.schedule_id = sch.id 
            JOIN service s ON a.service_id = s.id 
            WHERE a.type = ? " . $dateFilter . " 
            GROUP BY s.name 
            ORDER BY total 
            DESC LIMIT 5 
        ", array('regular')) as $a) {
            $services[] = $a['name'];
            $serviceTotals[] = (int)$a['total'];
        }
    
        $output->content = $this->app->template($this->app->dirRoot('res/app/view/admin/dashboard/index.html.php'), array(
            'app' => $this->app,
            'output' => $output,
            'flash' => $this->session->unset('flash'),
            'csrf_token' => $this->session->get('csrf_token'),
            'current_route' => $input->route,
            'user' => $this->session->get('user'),
            'notifications' => $this->messageRepo->all('WHERE receiver_id = ? AND read_at IS NULL ORDER BY sent_at DESC', array($userId)),
            'appt_by_status' => $apptByStatus,
            'dates' => $dates,
            'appt_per_day' => $apptPerDay,
            'services' => $services,
            'service_totals' => $serviceTotals,
            'filter' => $filter,
        ));
    
        return array($input, $output, $success);
    }
}