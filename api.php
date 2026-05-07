<?php
// =============================================
// api.php - All backend logic (save this file)
// =============================================
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

$dataDir = __DIR__ . '/data';
$logsDir = __DIR__ . '/logs';

if (!is_dir($dataDir)) mkdir($dataDir, 0755, true);
if (!is_dir($logsDir)) mkdir($logsDir, 0755, true);

$action = $_REQUEST['action'] ?? '';

function getActivities() {
    global $dataDir;
    $file = $dataDir . '/activities.json';
    if (!file_exists($file)) {
        file_put_contents($file, json_encode([], JSON_PRETTY_PRINT));
        return [];
    }
    return json_decode(file_get_contents($file), true) ?: [];
}

function saveActivities($list) {
    global $dataDir;
    $file = $dataDir . '/activities.json';
    file_put_contents($file, json_encode($list, JSON_PRETTY_PRINT));
}

function getLogFile($month, $year) {
    global $logsDir;
    return $logsDir . '/' . strtolower($month) . '_' . $year . '.json';
}

function getMonthData($month, $year) {
    $file = getLogFile($month, $year);
    if (!file_exists($file)) return [];
    return json_decode(file_get_contents($file), true) ?: [];
}

function saveMonthData($month, $year, $data) {
    $file = getLogFile($month, $year);
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

switch ($action) {
    // Get all activities
    case 'get_activities':
        echo json_encode(getActivities());
        break;

    // Add / Edit activity
    case 'save_activity':
        $list = getActivities();
        $id = $_POST['id'] ?? null;
        $name = trim($_POST['name'] ?? '');
        $desc = trim($_POST['description'] ?? '');
        $icon = trim($_POST['icon'] ?? 'fas fa-tasks');
        
        if (empty($name)) {
            echo json_encode(['success' => false, 'message' => 'Name required']);
            exit;
        }
        
        if ($id) {
            // edit
            foreach ($list as &$a) {
                if ($a['id'] == $id) {
                    $a['name'] = $name;
                    $a['description'] = $desc;
                    $a['icon'] = $icon;
                    break;
                }
            }
        } else {
            // new
            $maxId = 0;
            foreach ($list as $a) if ($a['id'] > $maxId) $maxId = $a['id'];
            $list[] = ['id' => $maxId + 1, 'name' => $name, 'description' => $desc, 'icon' => $icon];
        }
        
        saveActivities($list);
        echo json_encode(['success' => true]);
        break;

    // Delete activity
    case 'delete_activity':
        $id = (int)($_POST['id'] ?? 0);
        $list = array_filter(getActivities(), fn($a) => $a['id'] != $id);
        saveActivities(array_values($list));
        echo json_encode(['success' => true]);
        break;

    // Get completions for a specific date (default today)
    case 'get_completions':
        $date = $_GET['date'] ?? date('d-m-Y'); // Changed to DD-MM-YYYY format
        $ts = strtotime($date);
        $month = date('F', $ts);
        $year = date('Y', $ts);
        $dayKey = date('d-m-Y', $ts); // Changed to DD-MM-YYYY format
        
        $data = getMonthData($month, $year);
        echo json_encode($data[$dayKey] ?? []);
        break;

    // Mark / Unmark activity as completed
    case 'mark_completed':
    case 'unmark_completed':
        $actId = (int)($_POST['activity_id'] ?? 0);
        $date = $_POST['date'] ?? date('d-m-Y'); // Changed to DD-MM-YYYY format
        $completionSource = $_POST['completion_source'] ?? 'manual_click';
        $ts = strtotime($date);
        if (!$actId || !$ts) {
            echo json_encode(['success' => false]);
            exit;
        }
        
        $month = date('F', $ts);
        $year = date('Y', $ts);
        $dayKey = date('d-m-Y', $ts); // Changed to DD-MM-YYYY format
        
        $data = getMonthData($month, $year);
        if (!isset($data[$dayKey])) $data[$dayKey] = [];
        
        if ($action === 'mark_completed') {
            // avoid duplicates
            $exists = false;
            foreach ($data[$dayKey] as $e) {
                if ($e['activity_id'] === $actId) { $exists = true; break; }
            }
            if (!$exists) {
                // Get activity details for logging
                $activities = getActivities();
                $activity = null;
                foreach ($activities as $a) {
                    if ($a['id'] === $actId) {
                        $activity = $a;
                        break;
                    }
                }
                
                $data[$dayKey][] = [
                    'activity_id' => $actId,
                    'activity_name' => $activity['name'] ?? 'Unknown Activity',
                    'activity_icon' => $activity['icon'] ?? 'fas fa-tasks',
                    'completed_at' => date('d-m-Y H:i:s'), // Changed to DD-MM-YYYY format
                    'completion_source' => $completionSource
                ];
            }
        } else {
            // unmark
            $data[$dayKey] = array_filter($data[$dayKey], fn($e) => $e['activity_id'] !== $actId);
            $data[$dayKey] = array_values($data[$dayKey]);
        }
        
        if (empty($data[$dayKey])) unset($data[$dayKey]);
        
        saveMonthData($month, $year, $data);
        echo json_encode(['success' => true]);
        break;

    // Get full month log
    case 'get_month_log':
        $month = $_GET['month'] ?? date('F');
        $year = $_GET['year'] ?? date('Y');
        echo json_encode(getMonthData($month, $year));
        break;

    // Stats for a month (or entire year if month='all')
    case 'get_stats':
        $month = $_GET['month'] ?? date('F');
        $year = $_GET['year'] ?? date('Y');
        
        $activities = getActivities();
        $actNames = [];
        foreach ($activities as $a) $actNames[$a['id']] = $a['name'];
        
        $total = 0;
        $perAct = [];
        $daily = [];
        $monthsUsed = [];
        
        if ($month === 'all') {
            // Aggregate stats across all months in the year
            $logDir = __DIR__ . '/logs';
            if (is_dir($logDir)) {
                foreach (scandir($logDir) as $file) {
                    if (preg_match('/^(\w+)_' . $year . '\.json$/', $file, $matches)) {
                        $monthName = ucfirst($matches[1]);
                        $monthsUsed[] = $monthName;
                        $data = getMonthData($monthName, $year);
                        foreach ($data as $day => $list) {
                            $cnt = count($list);
                            $daily[$day] = $cnt;
                            $total += $cnt;
                            foreach ($list as $item) {
                                $aid = $item['activity_id'];
                                $perAct[$aid] = ($perAct[$aid] ?? 0) + 1;
                            }
                        }
                    }
                }
            }
        } else {
            $log = getMonthData($month, $year);
            foreach ($log as $day => $list) {
                $cnt = count($list);
                $daily[$day] = $cnt;
                $total += $cnt;
                foreach ($list as $item) {
                    $aid = $item['activity_id'];
                    $perAct[$aid] = ($perAct[$aid] ?? 0) + 1;
                }
            }
        }
        
        $namedPerAct = [];
        foreach ($perAct as $aid => $cnt) {
            if (isset($actNames[$aid])) $namedPerAct[$actNames[$aid]] = $cnt;
        }
        arsort($namedPerAct);
        
        echo json_encode([
            'total_completed' => $total,
            'active_days' => count($daily),
            'average_per_day' => count($daily) ? round($total / count($daily), 1) : 0,
            'per_activity' => $namedPerAct,
            'daily_counts' => $daily,
            'months_used' => $monthsUsed ?? []
        ]);
        break;

    // Get available years from logs
    case 'get_available_years':
        $logDir = __DIR__ . '/logs';
        $years = [];
        if (is_dir($logDir)) {
            foreach (scandir($logDir) as $file) {
                if (preg_match('/^(\w+)_(\d+)\.json$/', $file, $matches)) {
                    $years[] = $matches[2];
                }
            }
        }
        sort($years);
        echo json_encode(array_values(array_unique($years)));
        break;

    // Get available months for a given year
    case 'get_available_months':
        $year = $_GET['year'] ?? date('Y');
        $logDir = __DIR__ . '/logs';
        $months = [];
        if (is_dir($logDir)) {
            foreach (scandir($logDir) as $file) {
                if (preg_match('/^(\w+)_(\d+)\.json$/', $file, $matches)) {
                    if ($matches[2] == $year) {
                        $months[] = ucfirst($matches[1]);
                    }
                }
            }
        }
        $monthOrder = ["January","February","March","April","May","June","July","August","September","October","November","December"];
        usort($months, function($a, $b) use ($monthOrder) {
            return array_search($a, $monthOrder) - array_search($b, $monthOrder);
        });
        echo json_encode(array_values(array_unique($months)));
        break;

    default:
        echo json_encode(['error' => 'Unknown action']);
}
